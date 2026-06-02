<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customer';
    protected $primaryKey       = 'id_customer';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['nama', 'alamat', 'telepon', 'no_ktp', 'email', 'no_zip',];
    
    // Format Waktu Otomatis
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Validasi Internal Model (Opsional, tapi bagus untuk proteksi lapis kedua)
    protected $validationRules = [
        'nama'    => 'required|max_length[100]|min_length[3]',
        'alamat'  => 'required',
        'telepon' => 'required|numeric|min_length[10]',
        'no_ktp'  => 'required|numeric|exact_length[16]', // Dikunci tepat 16 digit angka KTP
        'email'   => 'permit_empty|valid_email',
        'no_zip'  => 'permit_empty|numeric',
    ];

    protected $validationMessages = [
        'no_ktp' => [
            'exact_length' => 'Nomor KTP harus tepat berukuran 16 digit angka.',
            'numeric'      => 'Nomor KTP hanya boleh berisi karakter angka.'
        ]
    ];
}