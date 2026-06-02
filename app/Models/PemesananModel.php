<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PemesananModel - Booking mobil dari customer
 * Sinkron dengan Database terupdate: db_showroom_mobil
 */
class PemesananModel extends Model
{
    protected $table            = 'pemesanan';
    protected $primaryKey       = 'id_pemesanan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    // Field di bawah ini disesuaikan 100% dengan struktur tabel terbaru kamu
    protected $allowedFields    = [
        'id_customer', 
        'id_mobil', 
        'id_user', 
        'tgl_pesan', 
        'tgl_jatuh_tempo',
        'harga_jadi', 
        'nilai_tanda_jadi', 
        'nilai_dp_minimal', 
        'status_pemesanan'
    ];

    // Aktifkan timestamp jika kamu menggunakan kolom created_at & updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua pemesanan beserta relasi untuk Dashboard & List
     */
    public function getAllWithRelasi(): array
    {
        return $this->select('pemesanan.*, customer.nama as nama_customer, customer.no_ktp,
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi')
                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                    ->join('mobil',    'mobil.id_mobil = pemesanan.id_mobil',           'left')
                    ->orderBy('pemesanan.tgl_pesan', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil detail pemesanan lengkap
     */
    public function getDetailWithRelasi(int $id): array|null
    {
        return $this->select('pemesanan.*, customer.nama as nama_customer, customer.alamat as alamat_customer,
                              customer.telepon, customer.no_ktp,
                              mobil.nama_mobil, mobil.merek, mobil.tipe, mobil.warna, mobil.no_polisi, mobil.tahun, mobil.harga_jual')
                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                    ->join('mobil',    'mobil.id_mobil = pemesanan.id_mobil',           'left')
                    ->where('pemesanan.id_pemesanan', $id)
                    ->first();
    }

    /**
     * ALIAS METHOD: Menjaga kompatibilitas panggilan di Controller Penjualan & Pemesanan
     */
    public function getDetailInvoice(int $id): array|null
    {
        return $this->getDetailWithRelasi($id);
    }

    /**
     * Hitung nominal DP (Default 30% dari harga deal)
     */
    public function hitungNominalDP(float $hargaJualJadi, float $persen = 30): float
    {
        return ($persen / 100) * $hargaJualJadi;
    }

    /**
     * Batal otomatis jika melewati jatuh tempo (Update status booking & rilis kembali unit mobil)
     */
    public function batalOtomatisTempo(): int
    {
        // Mencari pemesanan 'menunggu' yang tgl_jatuh_tempo nya sudah lewat dari hari ini
        $expired = $this->where('status_pemesanan', 'menunggu')
                        ->where('tgl_jatuh_tempo <', date('Y-m-d'))
                        ->findAll();

        if (empty($expired)) return 0;

        $ids = array_column($expired, 'id_pemesanan');
        $idMobils = array_column($expired, 'id_mobil');
        
        // 1. Update status pemesanan menjadi dibatalkan secara massal
        $this->builder()->whereIn('id_pemesanan', $ids)
                        ->update(['status_pemesanan' => 'dibatalkan']);
        
        // 2. Rilis kembali status unit mobil terkait menjadi 'tersedia' agar showroom tidak merugi
        if (!empty($idMobils)) {
            $db = \Config\Database::connect();
            $db->table('mobil')->whereIn('id_mobil', $idMobils)
                               ->update(['status_jual' => 'tersedia']);
        }
        
        return count($ids);
    }

    /**
     * Cek pemesanan yang sudah melewati jatuh tempo (status masih 'menunggu')
     * Digunakan di halaman cek-tempo untuk laporan pemesanan expired
     */
    public function cekTempo(): array
    {
        return $this->select('pemesanan.*, customer.nama as nama_customer,
                              mobil.nama_mobil, mobil.tipe, mobil.warna')
                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                    ->join('mobil',    'mobil.id_mobil = pemesanan.id_mobil',           'left')
                    ->where('status_pemesanan', 'menunggu')
                    ->where('tgl_jatuh_tempo <', date('Y-m-d'))
                    ->orderBy('tgl_jatuh_tempo', 'ASC')
                    ->findAll();
    }

    /**
     * Laporan pemesanan per periode
     */
    public function getLaporan(string $tglMulai, string $tglAkhir): array
    {
        return $this->select('pemesanan.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                    ->join('mobil',    'mobil.id_mobil = pemesanan.id_mobil',           'left')
                    ->where('tgl_pesan >=', $tglMulai)
                    ->where('tgl_pesan <=', $tglAkhir)
                    ->orderBy('pemesanan.tgl_pesan', 'ASC')
                    ->findAll();
    }
}