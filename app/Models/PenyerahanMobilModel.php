<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PenyerahanMobilModel
 * Proses bisnis:
 * - Unit: setelah STNK selesai + lunas
 * - STNK: ~2 minggu
 * - BPKB: ~2 bulan
 * - Jika diantar: buat Surat Jalan
 */
class PenyerahanMobilModel extends Model
{
    protected $table            = 'penyerahan_mobil';
    protected $primaryKey       = 'id_penyerahan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_penjualan', 'id_user', 'metode_serah', 'alamat_antar',
        'tgl_serah_unit', 'tgl_serah_stnk', 'tgl_serah_bpkb',
        'no_surat_jalan', 'kondisi_serah', 'catatan_petugas', 'estimasi_layan'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua penyerahan beserta relasi
     */
    public function getAllWithRelasi(): array
    {
        return $this->select('penyerahan_mobil.*, customer.nama as nama_customer, customer.telepon,
                              customer.alamat as alamat_customer,
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi,
                              penjualan.total_harga, penjualan.status_lunas,
                              users.nama as nama_petugas')
                    ->join('penjualan', 'penjualan.id_penjualan = penyerahan_mobil.id_penjualan', 'left')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan',         'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',            'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',                     'left')
                    ->join('users',     'users.id_user = penyerahan_mobil.id_user',                'left')
                    ->orderBy('penyerahan_mobil.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Detail penyerahan
     */
    public function getDetailWithRelasi(int $id): array|null
    {
        return $this->select('penyerahan_mobil.*, customer.nama as nama_customer, customer.telepon,
                              customer.alamat as alamat_customer, customer.no_ktp,
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi, mobil.tahun,
                              penjualan.total_harga, penjualan.status_lunas,
                              users.nama as nama_petugas')
                    ->join('penjualan', 'penjualan.id_penjualan = penyerahan_mobil.id_penjualan', 'left')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan',         'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',            'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',                     'left')
                    ->join('users',     'users.id_user = penyerahan_mobil.id_user',                'left')
                    ->where('penyerahan_mobil.id_penyerahan', $id)
                    ->first();
    }

    /**
     * Generate nomor surat jalan
     */
    public function generateNoSuratJalan(): string
    {
        $last = $this->selectMax('id_penyerahan')->first();
        $no   = ($last['id_penyerahan'] ?? 0) + 1;
        return 'SJ-' . date('Ymd') . '-' . str_pad($no, 4, '0', STR_PAD_LEFT);
    }
}
