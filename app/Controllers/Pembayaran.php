<?php

namespace App\Controllers;

use App\Models\PembayaranModel;
use App\Models\PenjualanModel;
use App\Models\PemesananModel;
use App\Models\MobilModel;
use CodeIgniter\Controller;

/**
 * Pembayaran Controller
 * Proses bisnis terintegrasi Showroom Mobil:
 * - Tunai: langsung terbit Kwitansi, potong sisa tagihan penjualan.
 * - Transfer: input bukti -> status pending -> admin verifikasi -> potong sisa tagihan & terbit Kwitansi.
 */
class Pembayaran extends Controller
{
    protected PembayaranModel $model;
    protected PenjualanModel  $penjualanModel;
    protected PemesananModel  $pemesananModel;
    protected MobilModel      $mobilModel;

    public function __construct()
    {
        $this->model          = new PembayaranModel();
        $this->penjualanModel = new PenjualanModel();
        $this->pemesananModel = new PemesananModel();
        $this->mobilModel     = new MobilModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        return view('pembayaran/index', [
            'title'      => 'Kelola Pembayaran',
            'pembayaran' => $this->model->getAllWithRelasi(),
            'pending'    => count($this->model->getMenungguVerifikasi()),
        ]);
    }

    public function create(int $idPenjualan)
    {
        $penjualan = $this->penjualanModel->getDetailWithRelasi($idPenjualan);
        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Data penjualan tidak ditemukan.');
        }
        return view('pembayaran/create', [
            'title'     => 'Input Pembayaran',
            'penjualan' => $penjualan,
        ]);
    }

    public function store()
    {
        $rules = [
            'id_penjualan'     => 'required|integer',
            'id_pemesanan'     => 'required|integer',
            'jenis_pembayaran' => 'required|in_list[bukti_pesan,dp,pelunasan,cicilan]',
            'metode_bayar'     => 'required|in_list[tunai,transfer]',
            'tgl_bayar'        => 'required|valid_date',
            'jumlah_bayar'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $metode = $this->request->getPost('metode_bayar');
        $jumlah = (float) str_replace([',', '.'], '', $this->request->getPost('jumlah_bayar'));

        // Handle file upload bukti transfer
        $buktiName = null;
        if ($metode === 'transfer') {
            $bukti = $this->request->getFile('bukti_transfer');
            if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
                $buktiName = $bukti->getRandomName();
                $bukti->move(ROOTPATH . 'public/uploads/bukti', $buktiName);
            }
        }

        // Penentuan nomor kwitansi otomatis khusus pembayaran tunai langsung
        $noKwitansi  = null;
        $statusVerif = 'menunggu';
        if ($metode === 'tunai') {
            $noKwitansi  = $this->model->generateNoKwitansi();
            $statusVerif = 'terverifikasi';
        }

        $idPenjualan = (int) $this->request->getPost('id_penjualan');

        // Insert data transaksi kas masuk
        $this->model->insert([
            'id_pemesanan'      => $this->request->getPost('id_pemesanan'),
            'id_penjualan'      => $idPenjualan,
            'id_user'           => session()->get('id_user'),
            'jenis_pembayaran'  => $this->request->getPost('jenis_pembayaran'),
            'metode_bayar'      => $metode,
            'tgl_bayar'         => $this->request->getPost('tgl_bayar'),
            'jumlah_bayar'      => $jumlah,
            'bukti_transfer'    => $buktiName,
            'no_kwitansi'       => $noKwitansi,
            'status_verifikasi' => $statusVerif,
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

        // AMAN: Update nominal dan pelunasan HANYA jika dana tunai (sudah sah masuk)
        if ($metode === 'tunai') {
            $this->syncKeuanganPenjualan($idPenjualan);
        }

        $msg = $metode === 'tunai'
            ? 'Pembayaran tunai berhasil diverifikasi sistem. No. Kwitansi: ' . $noKwitansi
            : 'Bukti transfer berhasil diunggah. Menunggu proses validasi dana oleh Admin.';

        return redirect()->to('/pembayaran')->with('success', $msg);
    }

    /** Verifikasi bukti transfer oleh admin -> menerbitkan nomor kwitansi resmi */
    public function verifikasi(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) {
            return redirect()->to('/pembayaran')->with('error', 'Data riwayat pembayaran tidak ditemukan.');
        }

        $noKwitansi = $this->model->generateNoKwitansi();
        $this->model->update($id, [
            'status_verifikasi' => 'terverifikasi',
            'no_kwitansi'       => $noKwitansi,
        ]);

        // Sinkronisasi data keuangan penjualan dipicu tepat setelah transfer divalidasi sah
        $this->syncKeuanganPenjualan($pembayaran['id_penjualan']);

        return redirect()->to('/pembayaran')->with('success', 'Dana transfer telah divalidasi. Kwitansi resmi terbit: ' . $noKwitansi);
    }

    /** Menolak berkas bukti transfer palsu / tidak valid */
    public function tolak(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) {
            return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');
        }

        $this->model->update($id, ['status_verifikasi' => 'ditolak']);
        return redirect()->to('/pembayaran')->with('warning', 'Validasi dana transfer ditolak.');
    }

    public function detail(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) {
            return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');
        }
        return view('pembayaran/detail', [
            'title'      => 'Detail Pembayaran', 
            'pembayaran' => $pembayaran
        ]);
    }

    /** Cetak Lembar Dokumen Kwitansi Showroom */
    public function cetakKwitansi(int $id)
    {
        $pembayaran = $this->model->getAllWithRelasi();
        $data       = array_filter($pembayaran, fn($p) => $p['id_pembayaran'] === $id);
        $data       = reset($data);
        
        if (!$data || $data['status_verifikasi'] !== 'terverifikasi') {
            return redirect()->to('/pembayaran')->with('error', 'Dokumen kwitansi tidak tersedia atau belum diverifikasi.');
        }
        return view('pembayaran/kwitansi', [
            'title'      => 'Cetak Kwitansi', 
            'pembayaran' => $data
        ]);
    }

    /** * Kumpulan Logika Inti (Helper Terisolasi)
     * Sinkronisasi data saldo kas masuk, kalkulasi sisa piutang, status lunas, 
     * hingga mengubah status unit mobil di showroom secara realtime.
     */
    private function syncKeuanganPenjualan(int $idPenjualan): void
    {
        $penjualan = $this->penjualanModel->find($idPenjualan);
        if (!$penjualan) return;

        // Hanya menghitung total pembayaran yang statusnya 'terverifikasi' (Sah secara hukum kasir)
        $totalDibayar = $this->model->getTotalBayarByPenjualan($idPenjualan);
        $sisa         = $penjualan['total_harga'] - $totalDibayar;
        $lunas        = $sisa <= 0 ? 'lunas' : 'belum_lunas';

        // Update rekapitulasi keuangan lembar penjualan kendaraan
        $this->penjualanModel->update($idPenjualan, [
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan'  => max(0, $sisa),
            'status_lunas'  => $lunas,
            'status_lulus'  => $lunas === 'lunas' ? 'lulus' : 'proses',
        ]);

        // Mengubah status inventori mobil menjadi TERJUAL jika pelunasan klir
        if ($lunas === 'lunas') {
            $pemesanan = $this->pemesananModel->find($penjualan['id_pemesanan']);
            if ($pemesanan) {
                // Perubahan status fisik unit mobil dan penutupan dokumen pemesanan awal
                $this->mobilModel->updateStatus($pemesanan['id_mobil'], 'terjual');
                $this->pemesananModel->update($pemesanan['id_pemesanan'], ['status_pemesanan' => 'selesai']);
            }
        }
    }
}