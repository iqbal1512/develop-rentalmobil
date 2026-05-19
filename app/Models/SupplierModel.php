<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'supplier';
    protected $primaryKey       = 'id_supplier';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nama_supplier', 'alamat', 'telepon', 'email', 'no_hp'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'nama_supplier' => 'required|max_length[100]',
        'alamat'        => 'required',
        'email'         => 'permit_empty|valid_email|max_length[100]',
        'no_hp'         => 'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [
        'nama_supplier' => ['required' => 'Nama supplier wajib diisi.'],
        'alamat'        => ['required' => 'Alamat wajib diisi.'],
        'email'         => ['valid_email' => 'Format email tidak valid.'],
    ];

    /**
     * Ambil supplier beserta jumlah mobil yang dimiliki
     */
    public function getSupplierWithMobil(): array
    {
        return $this->select('supplier.*, COUNT(mobil.id_mobil) as jumlah_mobil')
                    ->join('mobil', 'mobil.id_supplier = supplier.id_supplier', 'left')
                    ->groupBy('supplier.id_supplier')
                    ->findAll();
    }
}
