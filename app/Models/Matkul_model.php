<?php

namespace App\Models;

use CodeIgniter\Model;

class Matkul_model extends Model
{
    protected $table      = 'matkul'; // Nama tabel
    protected $primaryKey = 'id_matkul'; // Primary Key

    protected $allowedFields = ['kode_matkul', 'matkul', 'fakultas', 'prodi']; // Kolom yang boleh diisi
}


