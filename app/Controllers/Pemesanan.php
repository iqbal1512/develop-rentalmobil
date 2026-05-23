<?php

namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\CustomerModel;
use App\Models\MobilModel;
use App\Models\PenjualanModel;
use CodeIgniter\Controller;

/**
 * Pemesanan Controller
 * Proses bisnis:
 * 1. Customer bayar Bukti Pesanan Rp.500.000
 * 2. Sistem set jatuh tempo 7 hari
 * 3. Customer harus bayar DP 30% + serahkan KTP dalam 7 hari
 * 4. Jika tidak -> batal, bukti pesanan hangus
 */
class Pemesanan extends Controller
{
    protected PemesananModel $model;
    protected CustomerModel  $customerModel;
    protected MobilModel     $mobilModel;
    protected PenjualanModel $penjualanModel;

    public function __construct()
    {
        $this->model          = new PemesananModel();
        $this->customerModel  = new CustomerModel();
        $this->mobilModel     = new MobilModel();
        $this->penjualanModel = new PenjualanModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        // Cek dan batalkan pemesanan expired
        $dibatalkan = $this->model->batalOtomatisTempo();

        return view('pemesanan/index', [
            'title'      => 'Kelola Pemesanan Mobil',
            'pemesanan'  => $this->model->getAllWithRelasi(),
            'dibatalkan' => $dibatalkan,
        ]);
    }

    public function create(): string
    {
        return view('pemesanan/create', [
            'title'     => 'Buat Pemesanan Baru',
            'customers' => $this->customerModel->orderBy('nama')->findAll(),
            'mobils'    => $this->mobilModel->getMobilTersedia(),
        ]);
    }

    public function store()
    {
        $rules = [
            'id_customer'   => 'required|integer',
            'id_mobil'      => 'required|integer',
            'tgl_pesan'     => 'required|valid_date',
            'harga_jual'    => 'required',
            'harga_jual_jadi' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $hargaJualJadi = (float) str_replace([',', '.'], '', $this->request->getPost('harga_jual_jadi'));
        
        $inputDp = $this->request->getPost('nominal_dp');
        $nominalDp = !empty($inputDp) ? (float) str_replace([',', '.'], '', $inputDp) : $this->model->hitungNominalDP($hargaJualJadi, 30);
        $dpPersen = $hargaJualJadi > 0 ? round(($nominalDp / $hargaJualJadi) * 100, 2) : 30;
        
        $tglPesan      = $this->request->getPost('tgl_pesan');
        // Jatuh tempo 7 hari dari tgl pesan
        $tglJatuhTempo = date('Y-m-d', strtotime($tglPesan . ' +7 days'));

        $idPemesanan = $this->model->insert([
            'id_customer'      => $this->request->getPost('id_customer'),
            'id_mobil'         => $this->request->getPost('id_mobil'),
            'id_user'          => session()->get('id_user'),
            'tgl_pesan'        => $tglPesan,
            'tgl_jatuh_tempo'  => $tglJatuhTempo,
            'biaya_bukti_pesan'=> 500000,
            'harga_jual'       => (float) str_replace([',', '.'], '', $this->request->getPost('harga_jual')),
            'harga_jual_jadi'  => $hargaJualJadi,
            'dp_persen'        => $dpPersen,
            'nominal_dp'       => $nominalDp,
            'dp_awal_dibayar'  => 0,
            'sisa_dp_internal' => $nominalDp,
            'ktp_diterima'     => 0,
            'status_pemesanan' => 'menunggu',
            'catatan'          => $this->request->getPost('catatan'),
        ]);

        // Update status mobil jadi 'dipesan'
        $this->mobilModel->updateStatus($this->request->getPost('id_mobil'), 'dipesan');

        return redirect()->to('/pemesanan')->with('success',
            "Pemesanan berhasil dibuat. Jatuh tempo: {$tglJatuhTempo}. DP yang harus dibayar: Rp " . number_format($nominalDp, 0, ',', '.'));
    }

    public function edit(int $id)
    {
        $pemesanan = $this->model->getDetailWithRelasi($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Data tidak ditemukan.');
        }
        return view('pemesanan/edit', [
            'title'     => 'Edit Pemesanan',
            'pemesanan' => $pemesanan,
            'customers' => $this->customerModel->orderBy('nama')->findAll(),
            'mobils'    => $this->mobilModel->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $post = $this->request->getPost();
        $pemesanan = $this->model->find($id);
        $dpDibayar = (float) str_replace([',', '.'], '', $post['dp_awal_dibayar'] ?? $pemesanan['dp_awal_dibayar']);
        $nominalDp = (float) $pemesanan['nominal_dp'];
        $sisa      = $nominalDp - $dpDibayar;

        // Jika DP sudah lunas dan KTP diterima -> status dp_masuk
        $status = $pemesanan['status_pemesanan'];
        if ($dpDibayar >= $nominalDp && $post['ktp_diterima'] == 1) {
            $status = 'dp_masuk';
        }

        $this->model->update($id, [
            'dp_awal_dibayar'  => $dpDibayar,
            'sisa_dp_internal' => max(0, $sisa),
            'ktp_diterima'     => $post['ktp_diterima'] ?? 0,
            'status_pemesanan' => $post['status_pemesanan'] ?? $status,
            'catatan'          => $post['catatan'],
        ]);

        return redirect()->to('/pemesanan')->with('success', 'Data pemesanan berhasil diperbarui.');
    }

    public function detail(int $id)
    {
        $pemesanan = $this->model->getDetailWithRelasi($id);
        if (!$pemesanan) {
            return redirect()->to('/pemesanan')->with('error', 'Data tidak ditemukan.');
        }
        // Cek apakah sudah ada penjualan
        $penjualan = $this->penjualanModel->where('id_pemesanan', $id)->first();

        return view('pemesanan/detail', [
            'title'     => 'Detail Pemesanan',
            'pemesanan' => $pemesanan,
            'penjualan' => $penjualan,
        ]);
    }

    public function batal(int $id)
    {
        $pemesanan = $this->model->find($id);
        if ($pemesanan) {
            $this->model->update($id, ['status_pemesanan' => 'batal']);
            // Kembalikan status mobil ke tersedia
            $this->mobilModel->updateStatus($pemesanan['id_mobil'], 'tersedia');
        }
        return redirect()->to('/pemesanan')->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function delete(int $id)
    {
        $pemesanan = $this->model->find($id);
        if ($pemesanan) {
            $this->mobilModel->updateStatus($pemesanan['id_mobil'], 'tersedia');
            $this->model->delete($id);
        }
        return redirect()->to('/pemesanan')->with('success', 'Data pemesanan berhasil dihapus.');
    }

    /** Cek dan tampilkan pemesanan yang expired */
    public function cekTempo()
    {
        $expired = $this->model->cekTempo();
        return view('pemesanan/cek_tempo', [
            'title'   => 'Cek Jatuh Tempo',
            'expired' => $expired,
        ]);
    }
}
