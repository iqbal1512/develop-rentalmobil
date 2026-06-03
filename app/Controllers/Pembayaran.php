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

    public function create()
{
    $idPemesanan = $this->request->getGet('id_pemesanan');

    // Cek apakah sudah ada penjualan, jika belum, buatkan (inisialisasi)
    $penjualan = $this->penjualanModel->where('id_pemesanan', $idPemesanan)->first();
    
    if (!$penjualan) {
        $pemesanan = $this->pemesananModel->find($idPemesanan);
        $idPenjualan = $this->penjualanModel->insert([
            'id_pemesanan'     => $idPemesanan,
            'id_user'          => session()->get('id_user'),
            'tgl_penjualan'    => date('Y-m-d'),
            'total_tagihan'    => $pemesanan['harga_jadi'],
            'total_dibayar'    => 0,
            'sisa_tagihan'     => $pemesanan['harga_jadi'],
            'status_pelunasan' => 'belum_lunas'
        ]);
        $penjualan = $this->penjualanModel->find($idPenjualan);
    }

    return view('pembayaran/create', [
        'title'     => 'Input Pembayaran',
        'penjualan' => $penjualan // Data yang sudah ada di tabel penjualan
    ]);
}
public function store()
{
    // 1. Validasi Input
    $rules = [
        'id_penjualan'      => 'required|integer',
        'id_pemesanan'      => 'required|integer',
        'jenis_pembayaran'  => 'required',
        'metode_pembayaran' => 'required',
        'jumlah_bayar'      => 'required',
        'tgl_bayar'         => 'required|valid_date',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // 2. Bersihkan format angka (dari 1.000.000 ke 1000000)
    $jumlah = (float) str_replace([',', '.'], '', $this->request->getPost('jumlah_bayar'));

    // 3. Proses Bukti Transfer (jika ada)
    $buktiName = null;
    if ($this->request->getPost('metode_pembayaran') === 'transfer') {
        $bukti = $this->request->getFile('bukti_transfer');
        if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
            $buktiName = $bukti->getRandomName();
            $bukti->move(ROOTPATH . 'public/uploads/bukti', $buktiName);
        }
    }

    // 4. Insert data pembayaran
    $this->model->insert([
        'id_penjualan'      => $this->request->getPost('id_penjualan'),
        'id_pemesanan'      => $this->request->getPost('id_pemesanan'),
        'id_user'           => session()->get('id_user'),
        'jenis_pembayaran'  => $this->request->getPost('jenis_pembayaran'),
        'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
        'tgl_bayar'         => $this->request->getPost('tgl_bayar'),
        'jumlah_bayar'      => $jumlah,
        'bukti_tf'          => $buktiName,
        'status_verifikasi' => ($this->request->getPost('metode_pembayaran') === 'tunai' ? 'terverifikasi' : 'menunggu'),
    ]);

    // 5. Update otomatis saldo di tabel Penjualan
    $this->syncKeuanganPenjualan($this->request->getPost('id_penjualan'));

    return redirect()->to('/pembayaran')->with('success', 'Pembayaran berhasil disimpan.');
}

    public function verifikasi(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');

        $noKwitansi = $this->model->generateNoKwitansi();
        $this->model->update($id, [
            'status_verifikasi' => 'terverifikasi',
            'no_kwitansi'       => $noKwitansi,
        ]);

        $this->syncKeuanganPenjualan($pembayaran['id_penjualan']);
        return redirect()->to('/pembayaran')->with('success', 'Verifikasi berhasil.');
    }

    public function batalkan(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');

        $this->model->update($id, ['status_verifikasi' => 'pembatalan']);
        $this->syncKeuanganPenjualan($pembayaran['id_penjualan']);

        return redirect()->to('/pembayaran')->with('success', 'Pembayaran dibatalkan.');
    }

    public function tolak(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');

        $this->model->update($id, ['status_verifikasi' => 'ditolak']);
        return redirect()->to('/pembayaran')->with('warning', 'Validasi ditolak.');
    }

    public function detail(int $id)
    {
        $pembayaran = $this->model->find($id);
        if (!$pembayaran) return redirect()->to('/pembayaran')->with('error', 'Data tidak ditemukan.');
        
        return view('pembayaran/detail', ['title' => 'Detail Pembayaran', 'pembayaran' => $pembayaran]);
    }

    public function cetakKwitansi(int $id)
    {
        $pembayaran = $this->model->getAllWithRelasi();
        $data = array_filter($pembayaran, fn($p) => $p['id_pembayaran'] === $id);
        $data = reset($data);
        
        if (!$data || $data['status_verifikasi'] !== 'terverifikasi') {
            return redirect()->to('/pembayaran')->with('error', 'Kwitansi belum tersedia.');
        }
        return view('pembayaran/kwitansi', ['title' => 'Cetak Kwitansi', 'pembayaran' => $data]);
    }

    private function syncKeuanganPenjualan(int $idPenjualan): void
{
    $penjualan = $this->penjualanModel->find($idPenjualan);
    if (!$penjualan) return;

    $totalDibayar = $this->model->getTotalBayarByPenjualan($idPenjualan);
    
    // Gunakan 'total_tagihan' sesuai nama di DB kamu
    $sisa = $penjualan['total_tagihan'] - $totalDibayar;
    $lunas = $sisa <= 0 ? 'lunas' : 'belum_lunas';

    $this->penjualanModel->update($idPenjualan, [
        'total_dibayar'    => $totalDibayar,
        'sisa_tagihan'     => max(0, $sisa),
        'status_pelunasan' => $lunas, // Pastikan ini nama kolomnya
        'status_lulus'     => $lunas === 'lunas' ? 'lulus' : 'proses',
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