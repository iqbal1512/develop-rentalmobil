<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Field wajib terdaftar agar proses insert/update otomatis berjalan
    protected $allowedFields    = [
        'id_pemesanan', 
        'id_user', 
        'tgl_penjualan', 
        'total_harga', 
        'total_tagihan', 
        'total_dibayar', 
        'sisa_tagihan', 
        'status_lulus', 
        'status_lunas', 
        'proses_stnk', 
        'proses_bpkb', 
        'catatan'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua penjualan beserta relasi lengkap (untuk halaman utama Penjualan)
     */
    public function getAllWithRelasi(): array
    {
        return $this->select('penjualan.*, pemesanan.tgl_pesan, 
                              customer.nama as nama_customer, customer.telepon,
                              mobil.nama_mobil, mobil.tipe, users.nama as nama_user')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',    'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',             'left')
                    ->join('users',     'users.id_user = penjualan.id_user',               'left')
                    ->orderBy('penjualan.tgl_penjualan', 'DESC')
                    ->findAll();
    }

    /**
     * Detail penjualan untuk keperluan cetak/detail (singgle record)
     */
    public function getDetailWithRelasi(int $id): array|null
    {
        return $this->select('penjualan.*, pemesanan.harga_jadi, 
                              customer.nama as nama_customer, customer.alamat,
                              mobil.nama_mobil, mobil.no_polisi, users.nama as nama_user')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',    'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',             'left')
                    ->join('users',     'users.id_user = penjualan.id_user',               'left')
                    ->where('penjualan.id_penjualan', $id)
                    ->first();
    }

    /**
     * Total pendapatan berdasarkan total_tagihan (pastikan kolom ini ada di DB)
     */
    public function totalPendapatanBulanIni(): float
    {
        $result = $this->selectSum('total_tagihan', 'total_pendapatan')
                       ->where('MONTH(tgl_penjualan)', date('m'))
                       ->where('YEAR(tgl_penjualan)', date('Y'))
                       ->first();
        
        return (float)($result['total_pendapatan'] ?? 0);
    }
}