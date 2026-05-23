<?php

namespace App\Controllers;

use App\Models\PenyerahanMobilModel;
use App\Models\PenjualanModel;
use CodeIgniter\Controller;

/**
 * PenyerahanMobil Controller
 * Proses bisnis:
 * - Setelah STNK selesai + pelunasan -> serahkan unit
 * - Jika diantar: buat Surat Jalan
 * - BPKB diserahkan setelah ~2 bulan
 */
class PenyerahanMobil extends Controller
{
    protected PenyerahanMobilModel $model;
    protected PenjualanModel       $penjualanModel;

    public function __construct()
    {
        $this->model          = new PenyerahanMobilModel();
        $this->penjualanModel = new PenjualanModel();
        helper(['form', 'url']);
    }

    public function index(): string
    {
        return view('penyerahan/index', [
            'title'     => 'Kelola Penyerahan Mobil',
            'penyerahan'=> $this->model->getAllWithRelasi(),
        ]);
    }

    public function create(int $idPenjualan)
    {
        $penjualan = $this->penjualanModel->getDetailWithRelasi($idPenjualan);
        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Data penjualan tidak ditemukan.');
        }
        if ($penjualan['status_lunas'] !== 'lunas') {
            return redirect()->to('/penjualan/detail/' . $idPenjualan)
                             ->with('error', 'Pembayaran belum lunas. Penyerahan tidak dapat dilakukan.');
        }
        if ($penjualan['proses_stnk'] !== 'selesai') {
            return redirect()->to('/penjualan/detail/' . $idPenjualan)
                             ->with('warning', 'STNK belum selesai diurus.');
        }
        return view('penyerahan/create', [
            'title'     => 'Buat Penyerahan Mobil',
            'penjualan' => $penjualan,
        ]);
    }

    public function store()
    {
        $rules = [
            'id_penjualan'  => 'required|integer',
            'metode_serah'  => 'required|in_list[diambil,diantar]',
            'tgl_serah_unit'=> 'required|valid_date',
            'kondisi_serah' => 'required|in_list[baik,cacat,rusak]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $metode     = $this->request->getPost('metode_serah');
        $noSuratJalan = null;
        if ($metode === 'diantar') {
            $noSuratJalan = $this->model->generateNoSuratJalan();
        }

        $this->model->insert([
            'id_penjualan'   => $this->request->getPost('id_penjualan'),
            'id_user'        => session()->get('id_user'),
            'metode_serah'   => $metode,
            'alamat_antar'   => $this->request->getPost('alamat_antar'),
            'tgl_serah_unit' => $this->request->getPost('tgl_serah_unit'),
            'tgl_serah_stnk' => $this->request->getPost('tgl_serah_stnk'),
            'tgl_serah_bpkb' => $this->request->getPost('tgl_serah_bpkb'),
            'no_surat_jalan' => $noSuratJalan,
            'kondisi_serah'  => $this->request->getPost('kondisi_serah'),
            'catatan_petugas'=> $this->request->getPost('catatan_petugas'),
            'estimasi_layan' => $this->request->getPost('estimasi_layan'),
        ]);

        $msg = 'Penyerahan mobil berhasil dicatat.';
        if ($metode === 'diantar') {
            $msg .= ' Surat Jalan: ' . $noSuratJalan;
        }
        return redirect()->to('/penyerahan')->with('success', $msg);
    }

    public function edit(int $id)
    {
        $penyerahan = $this->model->getDetailWithRelasi($id);
        if (!$penyerahan) {
            return redirect()->to('/penyerahan')->with('error', 'Data tidak ditemukan.');
        }
        return view('penyerahan/edit', ['title' => 'Edit Penyerahan', 'penyerahan' => $penyerahan]);
    }

    public function update(int $id)
    {
        $this->model->update($id, [
            'tgl_serah_unit' => $this->request->getPost('tgl_serah_unit'),
            'tgl_serah_stnk' => $this->request->getPost('tgl_serah_stnk'),
            'tgl_serah_bpkb' => $this->request->getPost('tgl_serah_bpkb'),
            'kondisi_serah'  => $this->request->getPost('kondisi_serah'),
            'catatan_petugas'=> $this->request->getPost('catatan_petugas'),
        ]);
        return redirect()->to('/penyerahan')->with('success', 'Data penyerahan diperbarui.');
    }

    public function updateStnk(int $id)
    {
        $this->model->update($id, ['tgl_serah_stnk' => date('Y-m-d')]);
        return redirect()->to('/penyerahan')->with('success', 'STNK telah diserahkan.');
    }

    public function updateBpkb(int $id)
    {
        $this->model->update($id, ['tgl_serah_bpkb' => date('Y-m-d')]);
        return redirect()->to('/penyerahan')->with('success', 'BPKB telah diserahkan.');
    }

    public function cetakSuratJalan(int $id)
    {
        $penyerahan = $this->model->getDetailWithRelasi($id);
        if (!$penyerahan || $penyerahan['metode_serah'] !== 'diantar') {
            return redirect()->to('/penyerahan')->with('error', 'Surat jalan tidak tersedia.');
        }
        return view('penyerahan/surat_jalan', ['title' => 'Cetak Surat Jalan', 'penyerahan' => $penyerahan]);
    }
}
