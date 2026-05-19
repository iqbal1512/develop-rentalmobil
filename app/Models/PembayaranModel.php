<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PembayaranModel
 * Sinkron dengan Database: db_showroom_mobil
 */
class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_pemesanan', 'id_penjualan', 'id_user', 'jenis_pembayaran',
        'metode_bayar', 'tgl_bayar', 'jumlah_bayar', 'bukti_transfer',
        'no_kwitansi', 'status_verifikasi', 'keterangan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua pembayaran beserta relasi
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
     * Pembayaran menunggu verifikasi (khusus metode transfer)
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
     * Total bayar per penjualan (untuk pengecekan pelunasan)
     */
    public function getTotalBayarByPenjualan(int $idPenjualan): float
    {
        $result = $this->selectSum('jumlah_bayar')
                       ->where('id_penjualan', $idPenjualan)
                       ->where('status_verifikasi !=', 'ditolak')
                       ->first();
        
        return (float)($result['jumlah_bayar'] ?? 0);
    }

    /**
     * Otomatis Generate nomor kwitansi unik
     */
    public function generateNoKwitansi(): string
    {
        // Mencari ID terakhir untuk penomoran urut
        $builder = $this->db->table($this->table);
        $last = $builder->selectMax('id_pembayaran')->get()->getRowArray();
        
        $no = ($last['id_pembayaran'] ?? 0) + 1;
        return 'KWT-' . date('Ymd') . '-' . str_pad($no, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Filter laporan pembayaran berdasarkan rentang tanggal
     */
    public function getLaporan(string $tglMulai, string $tglAkhir): array
    {
        return $this->select('pembayaran.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->where('pembayaran.tgl_bayar >=', $tglMulai)
                    ->where('pembayaran.tgl_bayar <=', $tglAkhir)
                    ->orderBy('pembayaran.tgl_bayar', 'ASC')
                    ->findAll();
    }
}