<?php

namespace App\Models;
use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'akun';
    protected $allowedFields = ['nim', 'password', 'level'];
    protected $useTimestamps = true;

    public function getUser($nim = false)
    {
        if ($nim == false) {
            return $this->orderBy('level')->findAll();
        }

        return $this->where('nim', $nim)->first();
    }

    public function saveUser($data)
    {
        return $this->db->table('akun')->insert($data);
    }
}
