<?php

namespace App\Controllers;

use App\Models\PembayaranModel;
use App\Models\PenjualanModel;
use App\Models\PemesananModel;
use App\Models\MobilModel;
use CodeIgniter\Controller;

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
        $filter = $this->request->getGet('filter');
        $pembayaran = $this->model->getAllWithRelasi();

        if ($filter) {
            $pembayaran = array_filter($pembayaran, fn($p) => $p['jenis_pembayaran'] === $filter);
        }

        return view('pembayaran/index', [
            'title'        => 'Kelola Pembayaran',
            'pembayaran'   => $pembayaran,
            'pending'      => count($this->model->getMenungguVerifikasi()),
            'activeFilter' => $filter
        ]);
    }

    public function store()
    {
        // 1. Validasi Input
        $rules = [
            'id_pemesanan'     => 'required|integer',
            'jenis_pembayaran' => 'required|in_list[bukti_pesan,dp,pelunasan,cicilan]',
            'metode_bayar'     => 'required|in_list[tunai,transfer]',
            'tgl_bayar'        => 'required|valid_date',
            'jumlah_bayar'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Persiapan Data
        $metode = $this->request->getPost('metode_bayar');
        $jumlah = (float) str_replace([',', '.'], '', $this->request->getPost('jumlah_bayar'));
        $idPenjualan = $this->request->getPost('id_penjualan'); // Bisa kosong untuk DP

        // 3. Handle File Upload
        $buktiName = null;
        if ($metode === 'transfer') {
            $bukti = $this->request->getFile('bukti_transfer');
            if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
                $buktiName = $bukti->getRandomName();
                $bukti->move(ROOTPATH . 'public/uploads/bukti', $buktiName);
            }
        }

        // 4. Status Default
        $noKwitansi  = null;
        $statusVerif = 'menunggu';
        if ($metode === 'tunai') {
            $noKwitansi  = $this->model->generateNoKwitansi();
            $statusVerif = 'terverifikasi';
        }

        // 5. Insert ke Database (id_penjualan akan jadi NULL jika tidak ada)
        $this->model->insert([
            'id_pemesanan'      => $this->request->getPost('id_pemesanan'),
            'id_penjualan'      => !empty($idPenjualan) ? (int)$idPenjualan : null, 
            'id_user'           => session()->get('id_user'),
            'jenis_pembayaran'  => $this->request->getPost('jenis_pembayaran'),
            'metode_pembayaran' => $metode,
            'tgl_bayar'         => $this->request->getPost('tgl_bayar'),
            'jumlah_bayar'      => $jumlah,
            'bukti_tf'          => $buktiName,
            'no_kwitansi'       => $noKwitansi,
            'status_verifikasi' => $statusVerif,
            'keterangan'        => $this->request->getPost('keterangan'),
        ]);

        // 6. Sync hanya jika ada id_penjualan
        if ($metode === 'tunai' && !empty($idPenjualan)) {
            $this->syncKeuanganPenjualan((int)$idPenjualan);
        }

        return redirect()->to('/pembayaran')->with('success', 'Pembayaran berhasil diproses.');
    }

    // ... sisa fungsi lainnya (verifikasi, batalkan, dll) tetap sama
    // Pastikan syncKeuanganPenjualan hanya dipanggil jika $idPenjualan tidak null


    public function verifikasi(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data riwayat pembayaran tidak ditemukan.');

        $noKwitansi = $this->model->generateNoKwitansi();
        $this->model->update($id, [
            'status_verifikasi' => 'terverifikasi',
            'no_kwitansi'       => $noKwitansi,
        ]);

        $this->syncKeuanganPenjualan($pembayaran['id_penjualan']);
        return redirect()->to('/pembayaran')->with('success', 'Dana transfer telah divalidasi. Kwitansi resmi terbit: ' . $noKwitansi);
    }

    public function batalkan(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');

        $this->model->update($id, ['status_verifikasi' => 'pembatalan']);
        $this->syncKeuanganPenjualan($pembayaran['id_penjualan']);

        return redirect()->to('/pembayaran')->with('success', 'Pembayaran berhasil dibatalkan dan saldo penjualan disesuaikan.');
    }

    public function tolak(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');

        $this->model->update($id, ['status_verifikasi' => 'ditolak']);
        return redirect()->to('/pembayaran')->with('warning', 'Validasi dana transfer ditolak.');
    }

    public function detail(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');
        
        return view('pembayaran/detail', [
            'title'      => 'Detail Pembayaran', 
            'pembayaran' => $pembayaran
        ]);
    }

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

    private function syncKeuanganPenjualan(int $idPenjualan): void
    {
        $penjualan = $this->penjualanModel->find($idPenjualan);
        if (!$penjualan) return;

        $totalDibayar = $this->model->getTotalBayarByPenjualan($idPenjualan);
        $sisa         = $penjualan['total_harga'] - $totalDibayar;
        $lunas        = $sisa <= 0 ? 'lunas' : 'belum_lunas';

        $this->penjualanModel->update($idPenjualan, [
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan'  => max(0, $sisa),
            'status_lunas'  => $lunas,
            'status_lulus'  => $lunas === 'lunas' ? 'lulus' : 'proses',
        ]);

        if ($lunas === 'lunas') {
            $pemesanan = $this->pemesananModel->find($penjualan['id_pemesanan']);
            if ($pemesanan) {
                $this->mobilModel->updateStatus($pemesanan['id_mobil'], 'terjual');
                $this->pemesananModel->update($pemesanan['id_pemesanan'], ['status_pemesanan' => 'selesai']);
            }
        }
    }
}