<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PemesananModel - Booking mobil dari customer
 * Sinkron dengan Database: db_showroom_mobil
 */
class PemesananModel extends Model
{
    protected $table            = 'pemesanan';
    protected $primaryKey       = 'id_pemesanan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_customer', 'id_mobil', 'id_user', 'tgl_pesan', 'tgl_jatuh_tempo',
        'biaya_bukti_pesan', 'harga_jual', 'harga_jual_jadi', 'dp_persen',
        'nominal_dp', 'dp_awal_dibayar', 'sisa_dp_internal',
        'ktp_diterima', 'status_pemesanan', 'catatan'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua pemesanan beserta relasi untuk Dashboard & List
     */
    public function getAllWithRelasi(): array
    {
        return $this->select('pemesanan.*, customer.nama as nama_customer, customer.no_ktp,
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi,
                              users.nama as nama_user')
                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                    ->join('mobil',    'mobil.id_mobil = pemesanan.id_mobil',           'left')
                    ->join('users',    'users.id_user = pemesanan.id_user',             'left')
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
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi, mobil.tahun,
                              users.nama as nama_user')
                    ->join('customer', 'customer.id_customer = pemesanan.id_customer', 'left')
                    ->join('mobil',    'mobil.id_mobil = pemesanan.id_mobil',           'left')
                    ->join('users',    'users.id_user = pemesanan.id_user',             'left')
                    ->where('pemesanan.id_pemesanan', $id)
                    ->first();
    }

    /**
     * Hitung nominal DP (Default 30% dari harga deal)
     */
    public function hitungNominalDP(float $hargaJualJadi, float $persen = 30): float
    {
        return ($persen / 100) * $hargaJualJadi;
    }

    /**
     * Batal otomatis jika melewati jatuh tempo (Update sekaligus)
     * Digunakan di Dashboard Controller
     */
    public function batalOtomatisTempo(): int
    {
        // Mencari pemesanan 'menunggu' yang tgl_jatuh_tempo nya sudah lewat dari hari ini
        $expired = $this->where('status_pemesanan', 'menunggu')
                        ->where('tgl_jatuh_tempo <', date('Y-m-d'))
                        ->findAll();

        if (empty($expired)) return 0;

        $ids = array_column($expired, 'id_pemesanan');
        
        // Update status menjadi batal secara massal
        $this->builder()->whereIn('id_pemesanan', $ids)
                        ->update(['status_pemesanan' => 'batal']);
        
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