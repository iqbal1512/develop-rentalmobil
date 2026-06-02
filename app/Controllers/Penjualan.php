<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\PemesananModel;
// Perbaikan: Sesuaikan nama model pembayaran dengan standar model aplikasi kamu (misal: PembayaranPenjualanModel atau PembayaranModel)
use App\Models\PembayaranModel; 
use App\Models\PenyerahanMobilModel;
use App\Models\MobilModel;
use CodeIgniter\Controller;

/**
 * Penjualan Controller
 * Proses bisnis:
 * - Dibuat setelah status pemesanan = dp_masuk
 * - Pantau proses STNK (~2 minggu) dan BPKB (~2 bulan)
 * - Update status lunas saat pembayaran penuh diterima
 */
class Penjualan extends Controller
{
    protected PenjualanModel     $model;
    protected PemesananModel     $pemesananModel;
    protected PembayaranModel    $pembayaranModel;

    public function __construct()
    {
        $this->model           = new PenjualanModel();
        $this->pemesananModel  = new PemesananModel();
        $this->pembayaranModel = new PembayaranModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        return view('penjualan/index', [
            'title'     => 'Kelola Transaksi Penjualan',
            'penjualan' => $this->model->getAllWithRelasi(),
        ]);
    }

    /** Buat penjualan dari pemesanan yang sudah DP */
    public function create(int $idPemesanan)
    {
        $pemesanan = $this->pemesananModel->getDetailWithRelasi($idPemesanan);
        
        // Penguncian sistem: Pastikan pemesanan ada dan statusnya sudah DP_MASUK
        if (!$pemesanan || $pemesanan['status_pemesanan'] !== 'dp_masuk') {
            return redirect()->to('/pemesanan')->with('error', 'Pemesanan belum memenuhi syarat atau belum lunas DP untuk diproses ke Penjualan.');
        }

        return view('penjualan/create', [
            'title'     => 'Buat Transaksi Penjualan',
            'pemesanan' => $pemesanan,
        ]);
    }

    public function store()
    {
        $idPemesanan = (int) $this->request->getPost('id_pemesanan');
        $pemesanan   = $this->pemesananModel->find($idPemesanan);

        if (!$pemesanan) {
            return redirect()->to('/penjualan')->with('error', 'Data pemesanan utama tidak ditemukan.');
        }

        // PERBAIKAN: Sinkronisasi key array 100% dengan database PemesananModel kamu
        $totalHarga   = (float) ($pemesanan['harga_jadi'] ?? 0);
        $totalDibayar = (float) ($pemesanan['nilai_tanda_jadi'] ?? 0); 
        $sisaTagihan  = $totalHarga - $totalDibayar;

        // Ambil ID User yang sedang login dari session backend
        $idUser = session()->get('id_user') ?? session()->get('user_id') ?? 1;

        $idPenjualan = $this->model->insert([
            'id_pemesanan' => $idPemesanan,
            'id_user'      => $idUser,
            'tgl_penjualan'=> date('Y-m-d'),
            'total_harga'  => $totalHarga,
            'total_dibayar'=> $totalDibayar,
            'sisa_tagihan' => $sisaTagihan,
            'status_lulus' => 'proses', // Default awal alur berkas: PROSES
            'status_lunas' => $sisaTagihan <= 0 ? 'lunas' : 'belum_lunas',
            'proses_stnk'  => 'belum',
            'proses_bpkb'  => 'belum',
            'catatan'      => $this->request->getPost('catatan'),
        ]);

        // Update status alur pemesanan naik kelas menjadi 'diproses'
        $this->pemesananModel->update($idPemesanan, ['status_pemesanan' => 'diproses']);

        // Update mobil: set status_jual = 'terjual' dan potong stok unit showroom
        $mobilModel = new MobilModel();
        $mobil = $mobilModel->find($pemesanan['id_mobil']);
        if ($mobil) {
            $stokBaru = ($mobil['stok'] > 0) ? ($mobil['stok'] - 1) : 0;
            $mobilModel->update($pemesanan['id_mobil'], [
                'status_jual' => 'terjual',
                'stok'        => $stokBaru
            ]);
        }

        return redirect()->to('/penjualan/detail/' . $idPenjualan)
                         ->with('success', 'Berkas transaksi penjualan baru berhasil diterbitkan.');
    }

    public function detail(int $id)
    {
        $penjualan = $this->model->getDetailWithRelasi($id);
        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Data penjualan tidak ditemukan.');
        }

        // Ambil histori seluruh transaksi cicilan/pelunasan dari kas penjualan
        $pembayaran = $this->pembayaranModel->where('id_penjualan', $id)->findAll();

        // Cek data penyerahan unit mobil (Surat Jalan/BAST)
        $penyerahanModel = new PenyerahanMobilModel();
        $penyerahan = $penyerahanModel->where('id_penjualan', $id)->first();

        return view('penjualan/detail', [
            'title'      => 'Detail Transaksi Penjualan',
            'penjualan'  => $penjualan,
            'pembayaran' => $pembayaran,
            'penyerahan' => $penyerahan,
        ]);
    }

    public function edit(int $id)
    {
        $penjualan = $this->model->getDetailWithRelasi($id);
        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Data tidak ditemukan.');
        }
        return view('penjualan/edit', ['title' => 'Edit Penjualan', 'penjualan' => $penjualan]);
    }

    public function update(int $id)
    {
        $this->model->update($id, [
            'status_lulus' => $this->request->getPost('status_lulus'),
            'catatan'      => $this->request->getPost('catatan'),
        ]);
        return redirect()->to('/penjualan/detail/' . $id)->with('success', 'Data catatan penjualan berhasil diperbarui.');
    }

    /** Update status proses STNK (~2 minggu) secara berkala */
    public function updateStnk(int $id)
    {
        $penjualan = $this->model->find($id);
        if ($penjualan) {
            $current = $penjualan['proses_stnk'] ?? 'belum';
            $newStatus = 'belum';
            if ($current === 'belum')   $newStatus = 'proses';
            if ($current === 'proses')  $newStatus = 'selesai';
            if ($current === 'selesai') $newStatus = 'belum'; // Rotasi status klik toggle
            
            $this->model->update($id, ['proses_stnk' => $newStatus]);
        }
        return redirect()->to('/penjualan/detail/' . $id)->with('success', 'Status progress STNK berhasil diubah.');
    }

    /** Update status proses BPKB (~2 bulan) secara berkala */
    public function updateBpkb(int $id)
    {
        $penjualan = $this->model->find($id);
        if ($penjualan) {
            $current = $penjualan['proses_bpkb'] ?? 'belum';
            $newStatus = 'belum';
            if ($current === 'belum')   $newStatus = 'proses';
            if ($current === 'proses')  $newStatus = 'selesai';
            if ($current === 'selesai') $newStatus = 'belum'; // Rotasi status klik toggle
            
            $this->model->update($id, ['proses_bpkb' => $newStatus]);
        }
        return redirect()->to('/penjualan/detail/' . $id)->with('success', 'Status progress BPKB berhasil diubah.');
    }

    /** Fitur Cetak Invoice Penjualan Resmi */
    public function cetak(int $id)
    {
        $penjualan = $this->model->getDetailWithRelasi($id);
        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Data cetak tidak ditemukan.');
        }

        $pembayaran = $this->pembayaranModel->where('id_penjualan', $id)->findAll();

        return view('penjualan/cetak_pdf', [
            'title'      => 'FAKTUR_INVOICE_' . $id,
            'penjualan'  => $penjualan,
            'pembayaran' => $pembayaran
        ]);
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to('/penjualan')->with('success', 'Data transaksi penjualan berhasil dihapus.');
    }
}