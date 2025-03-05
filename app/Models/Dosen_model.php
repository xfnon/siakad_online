<?php

namespace App\Models;

use CodeIgniter\Model;

class Dosen_model extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    protected $allowedFields = ['nidn', 'kode_dosen', 'nama_dosen', 'jenis_kelamin'];
}

