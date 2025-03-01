<?php

namespace App\Models;

use CodeIgniter\Model;

class Jurusan_model extends Model
{
    protected $table = 'jurusan'; // Nama tabel
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode', 'nama'];

    public function saveJurusan($data)
    {
        return $this->db->table('jurusan')->insert($data);
    }

    
}