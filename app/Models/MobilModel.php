<?php

namespace App\Models;

use CodeIgniter\Model;

class MobilModel extends Model
{
    // 1. Nama tabel di database kamu
    protected $table            = 'mobil';
    
    // 2. Primary key dari tabel mobil
    protected $primaryKey       = 'id_mobil';
    
    // 3. Aktifkan auto increment jika id_mobil berupa INT AI
    protected $useAutoIncrement = true;
    
    // 4. Tipe data kembalian hasil query (array paling standar dan aman)
    protected $returnType       = 'array';
    
    // 5. Fitur soft delete (opsional, matikan jika langsung hapus permanen)
    protected $useSoftDeletes   = false;

    // 6. KOLOM YANG WAJIB DIKASIH IZIN (Sangat Krusial!)
    // Pastikan semua nama field ini sama persis dengan kolom di tabel database-mu
    protected $allowedFields    = [
        'nama_mobil',
        'merek',
        'varian',
        'tahun_pembuatan',
        'nomor_rangka',
        'nomor_mesin',
        'nomor_polisi',
        'warna',
        'kondisi',         // Baru / Bekas
        'harga_beli',
        'harga_jual',
        'foto_mobil',
        'status_jual',      // tersedia / dipesan / terjual
        'id_supplier'       // Foreign key relasi ke tabel supplier
    ];

    // 7. Fitur otomatisasi pencatatan waktu (opsional)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Method Kustom: Mengambil data mobil beserta nama suppliernya (Join)
     * Sangat berguna untuk halaman index.php (Daftar Mobil) nanti
     */
    public function getMobilWithSupplier($id = null)
    {
        if ($id === null) {
            return $this->select('mobil.*, supplier.nama_supplier')
                        ->join('supplier', 'supplier.id_supplier = mobil.id_supplier', 'left')
                        ->orderBy('mobil.id_mobil', 'DESC')
                        ->findAll();
        }

        return $this->select('mobil.*, supplier.nama_supplier')
                    ->join('supplier', 'supplier.id_supplier = mobil.id_supplier', 'left')
                    ->where('mobil.id_mobil', $id)
                    ->first();
    }

    /**
     * Mengambil daftar mobil yang statusnya ready stock / tersedia
     */
    public function getMobilTersedia(): array
    {
        // Query ini akan mengambil mobil yang status_jual-nya 'tersedia'
        return $this->where('status_jual', 'tersedia')
                    ->orderBy('nama_mobil', 'ASC')
                    ->findAll();
    }
}