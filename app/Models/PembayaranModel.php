<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran_penjualan';
    protected $primaryKey       = 'id_pembayaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    // Field sudah sinkron dengan Controller dan kebutuhan alur "Naik Pangkat"
    protected $allowedFields    = [
        'id_pemesanan',
        'id_penjualan',
        'jenis_pembayaran',
        'metode_pembayaran',
        'tgl_bayar',
        'jumlah_bayar',
        'bukti_tf',          
        'status_verifikasi', 
        'status_refund',
        'no_kwitansi',
        'keterangan' // Ditambahkan agar data keterangan dari form tersimpan
    ];

    protected $useTimestamps = false; 

    public function getAllWithRelasi(): array
    {
        return $this->select('pembayaran_penjualan.*, customer.nama as nama_customer, 
                              mobil.nama_mobil, penjualan.total_tagihan')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left')
                    ->join('penjualan', 'penjualan.id_penjualan = pembayaran_penjualan.id_penjualan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->orderBy('pembayaran_penjualan.tgl_bayar', 'DESC')
                    ->findAll();
    }

    public function getMenungguVerifikasi(): array
    {
        return $this->select('pembayaran_penjualan.*, customer.nama as nama_customer, mobil.nama_mobil')
                    ->join('pemesanan', 'pemesanan.id_pemesanan = pembayaran_penjualan.id_pemesanan', 'left')
                    ->join('customer',  'customer.id_customer = pemesanan.id_customer',     'left')
                    ->join('mobil',     'mobil.id_mobil = pemesanan.id_mobil',              'left')
                    ->where('pembayaran_penjualan.status_verifikasi', 'menunggu')
                    ->where('pembayaran_penjualan.metode_pembayaran', 'transfer')
                    ->findAll();
    }

    public function getTotalBayarByPenjualan(int $idPenjualan): float
    {
        $result = $this->selectSum('jumlah_bayar')
                       ->where('id_penjualan', $idPenjualan)
                       ->where('status_verifikasi', 'terverifikasi') 
                       ->first();
        
        return (float)($result['jumlah_bayar'] ?? 0);
    }

    public function generateNoKwitansi(): string
    {
        // Menggunakan query builder yang aman
        $last = $this->selectMax('id_pembayaran')->first();
        $no = ($last['id_pembayaran'] ?? 0) + 1;
        return 'KWT-' . date('Ymd') . '-' . str_pad($no, 4, '0', STR_PAD_LEFT);
    }

    public function getLaporan(string $tglMulai, string $tglAkhir): array
    {
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
}