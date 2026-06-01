<?php

namespace App\Controllers;

use App\Models\MobilModel;
use App\Models\SupplierModel;
use App\Models\CustomerModel;
use App\Models\PembelianModel;
use App\Models\PemesananModel;
use App\Models\PenjualanModel;
use App\Models\PembayaranModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $mobilModel      = new MobilModel();
        $supplierModel   = new SupplierModel();
        $customerModel   = new CustomerModel();
        $pembelianModel  = new PembelianModel();
        $pemesananModel  = new PemesananModel();
        $penjualanModel  = new PenjualanModel();
        $pembayaranModel = new PembayaranModel();

        // Jalankan pembatalan otomatis pemesanan expired
        $pemesananModel->batalOtomatisTempo();

        $allPemesanan = $pemesananModel->getAllWithRelasi();

        $data = [
            'title'                 => 'Dashboard',
            'total_mobil'           => $mobilModel->countAll(),
            'total_supplier'        => $supplierModel->countAll(),
            'total_customer'        => $customerModel->countAll(),
            'total_pemesanan_aktif' => $pemesananModel->whereIn('status_pemesanan', ['menunggu','dp_masuk','diproses'])->countAllResults(),
            'total_penjualan_bulan' => $penjualanModel->where("MONTH(tgl_penjualan) = MONTH(CURRENT_DATE)")->countAllResults(),
            'pendapatan_bulan'      => $penjualanModel->totalPendapatanBulanIni() ?? 0,
            
            // PERBAIKAN DI SINI: Gunakan 'status_verifikasi' sesuai database kamu
            'pembayaran_menunggu'   => $pembayaranModel->where('status_verifikasi', 'menunggu')->countAllResults(),
            
            'mobil_tersedia'        => $mobilModel->where('status_jual', 'tersedia')->countAllResults(),
            
            // Data untuk Tabel & Chart
            'pemesanan_terbaru'     => $allPemesanan ? array_slice($allPemesanan, 0, 5) : [],
            'pembayaran_pending'    => $pembayaranModel->getMenungguVerifikasi() ?? [],
            'chart_penjualan'       => $penjualanModel->getChartData6Bulan() ?? [],
            'status_mobil'          => $mobilModel->select('status_jual, count(*) as total')->groupBy('status_jual')->findAll(),
        ];

        return view('dashboard/index', $data);
    }
}