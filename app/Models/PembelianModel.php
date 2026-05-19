<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PembelianModel - Transaksi beli mobil dari supplier
 * Sinkron dengan Database: db_showroom_mobil
 */
class PembelianModel extends Model
{
    protected $table            = 'pembelian';
    protected $primaryKey       = 'id_pembelian';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_supplier', 'id_mobil', 'id_user', 'tgl_pembelian',
        'harga_beli', 'jumlah_pembelian', 'total_harga', 'metode_bayar',
        'bukti_transfer', 'no_kwitansi', 'status_pembelian', 'keterangan_kondisi'
    ];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua pembelian beserta relasi
     */
    public function getAllWithRelasi(): array
    {
        return $this->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil, mobil.tipe, users.nama as nama_user')
                    ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
                    ->join('mobil',    'mobil.id_mobil = pembelian.id_mobil',           'left')
                    ->join('users',    'users.id_user = pembelian.id_user',             'left')
                    ->orderBy('pembelian.tgl_pembelian', 'DESC')
                    ->findAll();
    }

    /**
     * Total pembelian bulan ini untuk Dashboard
     * Perbaikan: Menggunakan getRow() agar tidak error BaseConnection
     */
    public function totalBulanIni(): float
    {
        $result = $this->selectSum('total_harga')
                       ->where('MONTH(tgl_pembelian)', date('m'))
                       ->where('YEAR(tgl_pembelian)', date('Y'))
                       ->where('status_pembelian', 'selesai')
                       ->get()->getRowArray(); // Cara ambil data sum yang benar
        
        return (float)($result['total_harga'] ?? 0);
    }

    /**
     * Laporan pembelian per periode
     */
    public function getLaporan(string $tglMulai, string $tglAkhir): array
    {
        return $this->select('pembelian.*, supplier.nama_supplier, mobil.nama_mobil')
                    ->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left')
                    ->join('mobil',    'mobil.id_mobil = pembelian.id_mobil',           'left')
                    ->where('tgl_pembelian >=', $tglMulai)
                    ->where('tgl_pembelian <=', $tglAkhir)
                    ->orderBy('pembelian.tgl_pembelian', 'ASC')
                    ->findAll();
    }
}