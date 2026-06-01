<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table            = 'laporan';
    protected $primaryKey       = 'id_laporan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['jenis_laporan', 'periode_start_date', 'periode_akhir_date', 'dibuat_oleh'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = false;

    public function getAllWithUser(): array
    {
        return $this->select('laporan.*, users.nama as nama_user')
                    ->join('users', 'users.id_user = laporan.dibuat_oleh', 'left')
                    ->orderBy('laporan.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil satu laporan beserta nama user pembuatnya
     */
    public function findWithUser(int $id): array|null
    {
        return $this->select('laporan.*, users.nama as nama_user')
                    ->join('users', 'users.id_user = laporan.dibuat_oleh', 'left')
                    ->where('laporan.id_laporan', $id)
                    ->first();
    }
}
