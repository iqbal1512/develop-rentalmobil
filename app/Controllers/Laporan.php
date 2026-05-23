<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\PembelianModel;
use App\Models\PenjualanModel;
use App\Models\PembayaranModel;
use App\Models\PemesananModel;
use CodeIgniter\Controller;

class Laporan extends Controller
{
    protected LaporanModel   $model;
    protected PembelianModel $pembelianModel;
    protected PenjualanModel $penjualanModel;
    protected PembayaranModel $pembayaranModel;
    protected PemesananModel $pemesananModel;

    public function __construct()
    {
        $this->model           = new LaporanModel();
        $this->pembelianModel  = new PembelianModel();
        $this->penjualanModel  = new PenjualanModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->pemesananModel  = new PemesananModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        return view('laporan/index', [
            'title'   => 'Laporan',
            'laporan' => $this->model->getAllWithUser(),
        ]);
    }

    public function generate()
    {
        $jenis    = $this->request->getPost('jenis_laporan');
        $tglMulai = $this->request->getPost('periode_start_date');
        $tglAkhir = $this->request->getPost('periode_akhir_date');

        if (empty($jenis) || empty($tglMulai) || empty($tglAkhir)) {
            return redirect()->back()->with('error', 'Semua kolom harus diisi.');
        }

        $idLaporan = $this->model->insert([
            'jenis_laporan'      => $jenis,
            'periode_start_date' => $tglMulai,
            'periode_akhir_date' => $tglAkhir,
            'dibuat_oleh'        => session()->get('id_user'),
        ]);

        return redirect()->to('/laporan/cetak/' . $idLaporan);
    }

    public function cetak(int $id)
    {
        $laporan = $this->model->find($id);
        if (!$laporan) {
            return redirect()->to('/laporan')->with('error', 'Laporan tidak ditemukan.');
        }

        $tglMulai = $laporan['periode_start_date'];
        $tglAkhir = $laporan['periode_akhir_date'];
        $data     = [];
        $total    = 0;

        switch ($laporan['jenis_laporan']) {
            case 'pembelian':
                $data  = $this->pembelianModel->getLaporan($tglMulai, $tglAkhir);
                $total = array_sum(array_column($data, 'total_harga'));
                break;
            case 'penjualan':
                $data  = $this->penjualanModel->getLaporan($tglMulai, $tglAkhir);
                $total = array_sum(array_column($data, 'total_harga'));
                break;
            case 'pembayaran':
                $data  = $this->pembayaranModel->getLaporan($tglMulai, $tglAkhir);
                $total = array_sum(array_column($data, 'jumlah_bayar'));
                break;
            case 'pemesanan':
                $data  = $this->pemesananModel->getLaporan($tglMulai, $tglAkhir);
                $total = count($data);
                break;
        }

        return view('laporan/cetak', [
            'title'   => 'Laporan ' . ucfirst($laporan['jenis_laporan']),
            'laporan' => $laporan,
            'data'    => $data,
            'total'   => $total,
        ]);
    }
}
