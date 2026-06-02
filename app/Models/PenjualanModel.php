<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PenjualanModel
 * Sinkron dengan Database: db_showroom_mobil
 */
class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_pemesanan', 'id_user', 'tgl_penjualan', 'total_harga',
        'total_dibayar', 'sisa_tagihan', 'status_lulus', 'status_lunas',
        'proses_stnk', 'proses_bpkb', 'catatan'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua penjualan beserta relasi lengkap
     */
    public function getAllWithRelasi(): array
    {
        // PERBAIKAN: Mengubah pemesanan.harga_jual_jadi menjadi pemesanan.harga_jadi
        return $this->select('penjualan.*, pemesanan.tgl_pesan, pemesanan.harga_jadi,
                              customer.nama as nama_customer, customer.telepon,
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi,
                              users.nama as nama_user')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',    'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',             'left')
                    ->join('users',     'users.id_user = penjualan.id_user',               'left')
                    ->orderBy('penjualan.tgl_penjualan', 'DESC')
                    ->findAll();
    }

    /**
     * Detail penjualan untuk cetak nota/laporan
     */
    public function getDetailWithRelasi(int $id): array|null
    {
        // PERBAIKAN: Sinkronisasi field pemesanan agar sesuai dengan kolom harga_jadi dan nilai_tanda_jadi
        return $this->select('penjualan.*, pemesanan.tgl_pesan, pemesanan.harga_jadi, pemesanan.nilai_tanda_jadi,
                              customer.nama as nama_customer, customer.alamat as alamat_customer,
                              customer.telepon, customer.no_ktp,
                              mobil.nama_mobil, mobil.tipe, mobil.warna, mobil.no_polisi, mobil.tahun,
                              users.nama as nama_user')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',    'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',             'left')
                    ->join('users',     'users.id_user = penjualan.id_user',               'left')
                    ->where('penjualan.id_penjualan', $id)
                    ->first();
    }

    /**
     * Total pendapatan bulan ini (Berdasarkan total_harga di DB kamu)
     */
    public function totalPendapatanBulanIni(): float
    {
        $result = $this->selectSum('total_harga')
                       ->where('MONTH(tgl_penjualan)', date('m'))
                       ->where('YEAR(tgl_penjualan)', date('Y'))
                       ->first();
        
        return (float)($result['total_harga'] ?? 0);
    }

    /**
     * Data Grafik Penjualan 6 Bulan Terakhir
     */
    public function getChartData6Bulan(): array
    {
        return $this->select("DATE_FORMAT(tgl_penjualan, '%b') as bulan_label, COUNT(*) as total")
                    ->groupBy('MONTH(tgl_penjualan)')
                    ->orderBy('tgl_penjualan', 'ASC')
                    ->limit(6)
                    ->get()->getResultArray();
    }

    /**
     * Laporan penjualan per periode
     */
    public function getLaporan(string $tglMulai, string $tglAkhir): array
    {
        return $this->select('penjualan.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',    'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',             'left')
                    ->where('tgl_penjualan >=', $tglMulai)
                    ->where('tgl_penjualan <=', $tglAkhir)
                    ->orderBy('penjualan.tgl_penjualan', 'ASC')
                    ->findAll();
    }
}