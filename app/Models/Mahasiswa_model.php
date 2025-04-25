<?php

namespace App\Models;

use CodeIgniter\Model;

class Mahasiswa_model extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $allowedFields = ['nim', 'nama', 'jk', 'no_telp', 'alamat', 'prodi', 'angkatan', 'semester'];
}
