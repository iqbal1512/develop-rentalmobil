<?php

namespace App\Models;

use CodeIgniter\Model;

class MobilModel extends Model
{
    protected $table            = 'mobil';
    protected $primaryKey       = 'id_mobil';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id_supplier', 'nama_mobil', 'warna', 'vendor', 'tipe',
        'no_polisi', 'tahun', 'harga_beli', 'harga_jual', 'stok',
        'status_jual', 'status_mobil', 'foto', 'keterangan'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id_supplier' => 'required|integer',
        'nama_mobil'  => 'required|max_length[100]',
        'warna'       => 'required|max_length[50]',
        'vendor'      => 'required|max_length[50]',
        'tipe'        => 'required|max_length[50]',
        'harga_beli'  => 'required|decimal',
        'harga_jual'  => 'required|decimal',
    ];

    /**
     * Ambil semua mobil beserta nama supplier
     */
    public function getMobilWithSupplier(): array
    {
        return $this->select('mobil.*, supplier.nama_supplier')
                    ->join('supplier', 'supplier.id_supplier = mobil.id_supplier', 'left')
                    ->orderBy('mobil.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil mobil tersedia untuk dropdown pemesanan
     */
    public function getMobilTersedia(): array
    {
        return $this->where('status_jual', 'tersedia')
                    ->where('stok >', 0)
                    ->findAll();
    }

    /**
     * Update status jual mobil
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status_jual' => $status]);
    }
}
