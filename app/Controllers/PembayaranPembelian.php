<?php

namespace App\Controllers;

use App\Models\PembelianModel;
use App\Models\MobilModel;
use App\Models\SupplierModel;
use CodeIgniter\Controller;

class PembayaranPembelian extends Controller
{
    protected PembelianModel $pembelianModel;
    protected MobilModel     $mobilModel;
    protected SupplierModel  $supplierModel;

    public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->mobilModel     = new MobilModel();
        $this->supplierModel  = new SupplierModel();
        helper(['form', 'url']);
    }

    /**
     * Tampilan utama Pembayaran Pembelian
     * Menampilkan daftar pembelian yang belum lunas (proses) dan riwayat yang selesai
     */
    public function index(): string
    {
        $allPembelian = $this->pembelianModel->getAllWithRelasi();
        
        $belumBayar = array_filter($allPembelian, fn($p) => ($p['status_pembelian'] ?? 'proses') === 'proses');
        $riwayat   = array_filter($allPembelian, fn($p) => ($p['status_pembelian'] ?? 'proses') === 'selesai');

        return view('pembayaran_pembelian/index', [
            'title'      => 'Manajemen Pembayaran Pembelian',
            'belumBayar' => $belumBayar,
            'riwayat'    => $riwayat,
        ]);
    }

    /**
     * Form pembayaran pembelian
     */
    public function create(int $idPembelian)
    {
        $pembelian = $this->pembelianModel->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.warna, mobil.tipe')
                                          ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
                                          ->join('mobil',    'mobil.id_mobil = pembelian.id_mobil',           'left')
                                          ->find($idPembelian);

        if (!$pembelian) {
            return redirect()->to('/pembayaran_pembelian')->with('error', 'Transaksi pembelian tidak ditemukan.');
        }

        if ($pembelian['status_pembelian'] === 'selesai') {
            return redirect()->to('/pembayaran_pembelian')->with('warning', 'Transaksi pembelian ini sudah lunas dibayar.');
        }

        return view('pembayaran_pembelian/create', [
            'title'     => 'Input Pembayaran Pembelian',
            'pembelian' => $pembelian,
        ]);
    }

    /**
     * Proses simpan pembayaran pembelian
     */
    public function store()
    {
        $rules = [
            'id_pembelian' => 'required|integer',
            'metode_bayar' => 'required|in_list[tunai,transfer]',
            'jumlah_bayar' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idPembelian = (int) $this->request->getPost('id_pembelian');
        $pembelian   = $this->pembelianModel->find($idPembelian);

        if (!$pembelian) {
            return redirect()->to('/pembayaran_pembelian')->with('error', 'Data pembelian tidak ditemukan.');
        }

        $metode = $this->request->getPost('metode_bayar');
        $jumlah = (float) str_replace([',', '.'], '', $this->request->getPost('jumlah_bayar'));

        // Handle upload bukti transfer jika transfer
        $buktiName = null;
        if ($metode === 'transfer') {
            $bukti = $this->request->getFile('bukti_transfer');
            if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
                $buktiName = $bukti->getRandomName();
                $bukti->move(ROOTPATH . 'public/uploads/bukti', $buktiName);
            } else {
                return redirect()->back()->withInput()->with('error', 'Bukti transfer wajib diunggah untuk metode pembayaran transfer.');
            }
        }

        // Generate No Kwitansi
        $noKwitansi = 'PBL-KWT-' . date('Ymd') . '-' . rand(1000, 9999);

        // Update record pembelian
        $this->pembelianModel->update($idPembelian, [
            'metode_bayar'     => $metode,
            'bukti_transfer'   => $buktiName,
            'no_kwitansi'      => $noKwitansi,
            'status_pembelian' => 'selesai',
        ]);

        // Sesuai Activity Diagram: Update Status Mobil "Tersedia"
        $this->mobilModel->updateStatus($pembelian['id_mobil'], 'tersedia');

        // Note: Stok mobil sudah ditambahkan pada saat Pembelian::store(), jadi tidak perlu ditambahkan lagi di sini.

        $msg = "Pembayaran pembelian berhasil disimpan. No. Kwitansi: {$noKwitansi}";
        return redirect()->to('/pembayaran_pembelian')->with('success', $msg);
    }

    /**
     * Detail pembayaran pembelian
     */
    public function detail(int $idPembelian)
    {
        $pembelian = $this->pembelianModel->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.warna, mobil.tipe, users.nama as nama_user')
                                          ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
                                          ->join('mobil',    'mobil.id_mobil = pembelian.id_mobil',           'left')
                                          ->join('users',    'users.id_user = pembelian.id_user',             'left')
                                          ->find($idPembelian);

        if (!$pembelian) {
            return redirect()->to('/pembayaran_pembelian')->with('error', 'Data tidak ditemukan.');
        }

        return view('pembayaran_pembelian/detail', [
            'title'     => 'Detail Pembayaran Pembelian',
            'pembelian' => $pembelian,
        ]);
    }

    /**
     * Cetak Kwitansi Pembayaran Pembelian
     */
    public function cetakKwitansi(int $idPembelian)
    {
        $pembelian = $this->pembelianModel->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.warna, mobil.tipe, users.nama as nama_user')
                                          ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
                                          ->join('mobil',    'mobil.id_mobil = pembelian.id_mobil',           'left')
                                          ->join('users',    'users.id_user = pembelian.id_user',             'left')
                                          ->find($idPembelian);

        if (!$pembelian || $pembelian['status_pembelian'] !== 'selesai') {
            return redirect()->to('/pembayaran_pembelian')->with('error', 'Kwitansi tidak tersedia.');
        }

        return view('pembayaran_pembelian/kwitansi', [
            'title'     => 'Cetak Kwitansi Pembelian',
            'pembelian' => $pembelian,
        ]);
    }
}
