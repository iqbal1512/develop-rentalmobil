<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['username', 'password', 'nama', 'role', 'value'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[6]',
        'nama'     => 'required|max_length[100]',
        'role'     => 'required|in_list[admin,owner]',
    ];

    /**
     * Cek login: username + password (Activity Diagram Login)
     */
    public function cekLogin(string $username, string $password): array|false
    {
        $user = $this->where('username', $username)
                     ->where('value', 1)
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
