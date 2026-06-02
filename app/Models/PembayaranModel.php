<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran_penjualan'; // FIXED: Sesuai DB
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // FIELD SUDAH DISESUAIKAN 100% SAMA SCREENSHOT DB KAMU
    protected $allowedFields    = [
        'id_pemesanan',
        'id_penjualan',
        'jenis_pembayaran',
        'metode_pembayaran', // FIXED: Di DB kamu namanya metode_pembayaran (bukan metode_bayar)
        'tgl_bayar',
        'jumlah_bayar',
        'bukti_tf',          
        'status_verifikasi', 
        'status_refund'      
    ];

    // Format Waktu (Pindahkan ke dalam class agar tidak parse error)
    protected $useTimestamps = false; 

    /**
     * Ambil semua data pembayaran masuk beserta relasi data customer dan unit mobil
     */
    public function getAllWithRelasi(): array
    {
        // PERBAIKAN: Semua alias tabel 'pembayaran' diganti ke 'pembayaran_penjualan'
        // Kolom 'penjualan.total_harga' disesuaikan jika nanti ada penyesuaian di tabel penjualan
        return $this->select('pembayaran_penjualan.*, customer.nama as nama_customer, 
                             mobil.nama_mobil, penjualan.total_tagihan')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left')
                    ->join('penjualan', 'penjualan.id_penjualan = pembayaran_penjualan.id_penjualan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->orderBy('pembayaran_penjualan.tgl_bayar', 'DESC')
                    ->findAll();
    }

    /**
     * Mengambil daftar pembayaran yang menunggu validasi admin (Khusus transfer bank)
     */
    public function getMenungguVerifikasi(): array
    {
        // PERBAIKAN: Mengganti 'pembayaran.status_verifikasi' menjadi 'pembayaran_penjualan.status_verifikasi'
        // PERBAIKAN: Mengganti 'pembayaran.metode_bayar' menjadi 'pembayaran_penjualan.metode_pembayaran' (sesuai DB)
        return $this->select('pembayaran_penjualan.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->where('pembayaran_penjualan.status_verifikasi', 'menunggu')
                    ->where('pembayaran_penjualan.metode_pembayaran', 'transfer')
                    ->findAll();
    }

    /**
     * Menghitung total dana masuk yang sah (Terverifikasi) per lembar penjualan
     */
    public function getTotalBayarByPenjualan(int $idPenjualan): float
    {
        $result = $this->selectSum('jumlah_bayar')
                       ->where('id_penjualan', $idPenjualan)
                       ->where('status_verifikasi', 'terverifikasi') 
                       ->first();
        
        return (float)($result['jumlah_bayar'] ?? 0);
    }

    /**
     * Otomatis membuat format nomor kwitansi unik showroom
     */
    public function generateNoKwitansi(): string
    {
        $builder = $this->db->table($this->table);
        $last = $builder->selectMax('id_pembayaran')->get()->getRowArray();
        
        $no = ($last['id_pembayaran'] ?? 0) + 1;
        return 'KWT-' . date('Ymd') . '-' . str_pad($no, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Filter data transaksi kas masuk untuk keperluan cetak laporan omset berkala
     */
    public function getLaporan(string $tglMulai, string $tglAkhir): array
    {
        // PERBAIKAN: Menyelaraskan seluruh prefix tabel 'pembayaran' menjadi 'pembayaran_penjualan'
        return $this->select('pembayaran_penjualan.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->where('pembayaran_penjualan.tgl_bayar >=', $tglMulai)
                    ->where('pembayaran_penjualan.tgl_bayar <=', $tglAkhir)
                    ->where('pembayaran_penjualan.status_verifikasi', 'terverifikasi') 
                    ->orderBy('pembayaran_penjualan.tgl_bayar', 'ASC')
                    ->findAll();
    }
} // Tanda penutup class SEHARUSNYA di paling bawah ini!