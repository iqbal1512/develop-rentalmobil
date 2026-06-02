<?php

namespace App\Controllers;

use App\Models\MobilModel;
use App\Models\SupplierModel;
use App\Models\CustomerModel;
use App\Models\PemesananModel;
use App\Models\PenjualanModel;
use App\Models\PembayaranModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Pastikan user sudah login, jika belum arahkan ke login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Inisialisasi semua model yang dibutuhkan
        $mobilModel      = new MobilModel();
        $supplierModel   = new SupplierModel();
        $customerModel   = new CustomerModel();
        $pemesananModel  = new PemesananModel();
        $penjualanModel  = new PenjualanModel();
        $pembayaranModel = new PembayaranModel();

        // 1. Jalankan fungsi otomatis pembatalan jika ada pemesanan yang jatuh tempo (Tempo Elicitation)
        if (method_exists($pemesananModel, 'batalOtomatisTempo')) {
            $pemesananModel->batalOtomatisTempo();
        }

        // 2. HITUNG TOTAL DATA (Untuk Stat Cards Baris 1)
        $data['total_mobil']            = $mobilModel->countAll();
        $data['total_supplier']         = $supplierModel->countAll();
        $data['total_customer']         = $customerModel->countAll();
        $data['total_pemesanan_aktif']   = $pemesananModel->where('status_pemesanan', 'aktif')->countAllResults();

        // 3. HITUNG OPERASIONAL & FINANSIAL (Untuk Stat Cards Baris 2)
        // Hitung penjualan bulan ini
        $data['total_penjualan_bulan']  = $penjualanModel->where("MONTH(tgl_penjualan) = MONTH(CURRENT_DATE)")
                                                         ->where("YEAR(tgl_penjualan) = YEAR(CURRENT_DATE)")
                                                         ->countAllResults();

        // Ambil nominal pendapatan bulan ini (Gunakan fallback aman jika nama kolom berbeda)
        $db = \Config\Database::connect();
        $builderPenjualan = $db->table('penjualan');
        if ($db->fieldExists('total_bayar', 'penjualan')) {
            $queryPendapatan = $builderPenjualan->selectSum('total_bayar', 'omset')
                                                ->where("MONTH(tgl_penjualan) = MONTH(CURRENT_DATE)")
                                                ->where("YEAR(tgl_penjualan) = YEAR(CURRENT_DATE)")
                                                ->get()->getRowArray();
            $data['pendapatan_bulan'] = $queryPendapatan['omset'] ?? 0;
        } elseif ($db->fieldExists('harga_jual', 'penjualan')) {
            $queryPendapatan = $builderPenjualan->selectSum('harga_jual', 'omset')
                                                ->where("MONTH(tgl_penjualan) = MONTH(CURRENT_DATE)")
                                                ->where("YEAR(tgl_penjualan) = YEAR(CURRENT_DATE)")
                                                ->get()->getRowArray();
            $data['pendapatan_bulan'] = $queryPendapatan['omset'] ?? 0;
        } else {
            $data['pendapatan_bulan'] = 0; 
        }

        // SINKRONISASI 1: Hitung pembayaran menunggu berdasarkan kolom 'status_verifikasi'
        $data['pembayaran_menunggu']    = $pembayaranModel->where('status_verifikasi', 'menunggu')->countAllResults();
        
        // Hitung mobil yang ready stok
        $data['mobil_tersedia']         = $mobilModel->where('status_jual', 'tersedia')->countAllResults();

        // 4. DATA UNTUK DOUGHNUT CHART (Status Mobil)
        $data['status_mobil'] = $mobilModel->select('status_jual, COUNT(id_mobil) as total')
                                           ->groupBy('status_jual')
                                           ->findAll();

        // 5. DATA UNTUK BAR CHART (Tren Penjualan 6 Bulan Terakhir)
        $data['chart_penjualan'] = $penjualanModel->select("DATE_FORMAT(tgl_penjualan, '%b') as bulan, COUNT(id_penjualan) as total")
                                                  ->where("tgl_penjualan >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)")
                                                  ->groupBy("MONTH(tgl_penjualan)")
                                                  ->orderBy("tgl_penjualan", "ASC")
                                                  ->findAll();

        // 6. LIVE DATA TABEL PEMESANAN TERBARU
        $data['pemesanan_terbaru'] = $pemesananModel->select('pemesanan.*, customer.nama as nama_customer, mobil.nama_mobil')
                                                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                                                    ->join('mobil', 'mobil.id_mobil = pemesanan.id_mobil', 'left')
                                                    ->orderBy('pemesanan.id_pemesanan', 'DESC')
                                                    ->findAll(5);

        // SINKRONISASI 2: Live data pembayaran pending sesuai struktur relasi DB barumu
        // Karena data customer nempel di tabel pemesanan/penjualan, kita join lewat jalur yang valid
        $data['pembayaran_pending'] = $pembayaranModel->select('pembayaran_penjualan.*, customer.nama as nama_customer')
                                                      ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left')
                                                      ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                                                      ->where('pembayaran_penjualan.status_verifikasi', 'menunggu')
                                                      ->orderBy('pembayaran_penjualan.id_pembayaran', 'DESC')
                                                      ->findAll(5);

        // Lempar data ke view dashboard utama
        return view('dashboard/index', $data);
    }
}