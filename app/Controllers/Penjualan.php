<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\PemesananModel;
use App\Models\PembayaranModel;
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
    protected PenjualanModel  $model;
    protected PemesananModel  $pemesananModel;
    protected PembayaranModel $pembayaranModel;

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
    public function create(int $idPemesanan): string
    {
        $pemesanan = $this->pemesananModel->getDetailWithRelasi($idPemesanan);
        if (!$pemesanan || $pemesanan['status_pemesanan'] !== 'dp_masuk') {
            return redirect()->to('/pemesanan')->with('error', 'Pemesanan belum memenuhi syarat untuk dibuat penjualan.');
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
            return redirect()->to('/penjualan')->with('error', 'Data pemesanan tidak ditemukan.');
        }

        $totalHarga  = (float) $pemesanan['harga_jual_jadi'];
        $totalDibayar= (float) $pemesanan['dp_awal_dibayar'] + (float) $pemesanan['biaya_bukti_pesan'];
        $sisaTagihan = $totalHarga - $totalDibayar;

        $idPenjualan = $this->model->insert([
            'id_pemesanan' => $idPemesanan,
            'id_user'      => session()->get('id_user'),
            'tgl_penjualan'=> date('Y-m-d'),
            'total_harga'  => $totalHarga,
            'total_dibayar'=> $totalDibayar,
            'sisa_tagihan' => $sisaTagihan,
            'status_lulus' => 'proses',
            'status_lunas' => $sisaTagihan <= 0 ? 'lunas' : 'belum_lunas',
            'proses_stnk'  => 'belum',
            'proses_bpkb'  => 'belum',
            'catatan'      => $this->request->getPost('catatan'),
        ]);

        // Update status pemesanan
        $this->pemesananModel->update($idPemesanan, ['status_pemesanan' => 'diproses']);

        // Update mobil: set status_jual = 'terjual' dan kurangi stok
        $mobilModel = new \App\Models\MobilModel();
        $mobil = $mobilModel->find($pemesanan['id_mobil']);
        if ($mobil && $mobil['stok'] > 0) {
            $mobilModel->update($pemesanan['id_mobil'], [
                'status_jual' => 'terjual',
                'stok'        => $mobil['stok'] - 1
            ]);
        }

        return redirect()->to('/penjualan/detail/' . $idPenjualan)
                         ->with('success', 'Transaksi penjualan berhasil dibuat.');
    }

    public function detail(int $id): string
    {
        $penjualan = $this->model->getDetailWithRelasi($id);
        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Data tidak ditemukan.');
        }
        $pembayaran = $this->pembayaranModel->where('id_penjualan', $id)->findAll();

        return view('penjualan/detail', [
            'title'     => 'Detail Penjualan',
            'penjualan' => $penjualan,
            'pembayaran'=> $pembayaran,
        ]);
    }

    public function edit(int $id): string
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
        return redirect()->to('/penjualan')->with('success', 'Data penjualan diperbarui.');
    }

    /** Update status proses STNK (~2 minggu) */
    public function updateStnk(int $id)
    {
        $penjualan = $this->model->find($id);
        if ($penjualan) {
            $newStatus = $penjualan['proses_stnk'] === 'belum' ? 'proses' : 'selesai';
            $this->model->update($id, ['proses_stnk' => $newStatus]);
        }
        return redirect()->to('/penjualan/detail/' . $id)->with('success', 'Status STNK diperbarui.');
    }

    /** Update status proses BPKB (~2 bulan) */
    public function updateBpkb(int $id)
    {
        $penjualan = $this->model->find($id);
        if ($penjualan) {
            $newStatus = $penjualan['proses_bpkb'] === 'belum' ? 'proses' : 'selesai';
            $this->model->update($id, ['proses_bpkb' => $newStatus]);
        }
        return redirect()->to('/penjualan/detail/' . $id)->with('success', 'Status BPKB diperbarui.');
    }

    public function delete(int $id)
    {
        $this->model->delete($id);
        return redirect()->to('/penjualan')->with('success', 'Data penjualan dihapus.');
    }
}
