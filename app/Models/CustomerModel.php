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
    protected $allowedFields    = ['nama', 'alamat', 'telepon', 'no_ktp', 'email', 'no_zip', 'foto_ktp'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'nama'   => 'required|max_length[100]',
        'alamat' => 'required',
        'no_ktp' => 'required|min_length[16]|max_length[30]',
        'email'  => 'permit_empty|valid_email',
    ];
}
