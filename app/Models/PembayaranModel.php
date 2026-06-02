<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PembayaranPenjualanModel
 * Mengelola arus kas masuk (pembayaran dari customer atas unit kendaraan)
 * Sinkron dengan Database: db_showroom_mobil
 */
class PembayaranPenjualanModel extends Model
{
    protected $table            = 'pembayaran'; // Tetap menggunakan tabel pembayaran utama
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_pemesanan', 'id_penjualan', 'id_user', 'jenis_pembayaran',
        'metode_bayar', 'tgl_bayar', 'jumlah_bayar', 'bukti_transfer',
        'no_kwitansi', 'status_verifikasi', 'keterangan'
    ];

    // Format Waktu
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua data pembayaran masuk beserta relasi data customer dan unit mobil
     */
    public function getAllWithRelasi(): array
    {
        return $this->select('pembayaran.*, customer.nama as nama_customer, 
                              mobil.nama_mobil, users.nama as nama_user, 
                              penjualan.total_harga')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran.id_pemesanan', 'left')
                    ->join('penjualan', 'penjualan.id_penjualan = pembayaran.id_penjualan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->join('users',     'users.id_user = pembayaran.id_user',               'left')
                    ->orderBy('pembayaran.tgl_bayar', 'DESC')
                    ->findAll();
    }

    /**
     * Mengambil daftar pembayaran yang menunggu validasi admin (Khusus transfer bank)
     */
    public function getMenungguVerifikasi(): array
    {
        return $this->select('pembayaran.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->where('pembayaran.status_verifikasi', 'menunggu')
                    ->where('pembayaran.metode_bayar', 'transfer')
                    ->findAll();
    }

    /**
     * Menghitung total dana masuk yang sah (Terverifikasi) per lembar penjualan
     * PERBAIKAN LOGIKA: Uang yang berstatus 'menunggu' verifikasi transfer 
     * TIDAK BOLEH ikut terjumlah sebagai saldo pelunasan.
     */
    public function getTotalBayarByPenjualan(int $idPenjualan): float
    {
        $result = $this->selectSum('jumlah_bayar')
                       ->where('id_penjualan', $idPenjualan)
                       ->where('status_verifikasi', 'terverifikasi') // HANYA hitung dana yang sudah sah/klir
                       ->first();
        
        return (float)($result['jumlah_bayar'] ?? 0);
    }

    /**
     * Otomatis membuat format nomor kwitansi unik showroom (Contoh: KWT-20260602-0001)
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
        return $this->select('pembayaran.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->where('pembayaran.tgl_bayar >=', $tglMulai)
                    ->where('pembayaran.tgl_bayar <=', $tglAkhir)
                    ->where('pembayaran.status_verifikasi', 'terverifikasi') // Laporan hanya mencatat uang riil yang sah
                    ->orderBy('pembayaran.tgl_bayar', 'ASC')
                    ->findAll();
    }
}