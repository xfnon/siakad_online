<?php

namespace App\Models;

use CodeIgniter\Model;

class Dosen_model extends Model
{
    protected $table = 'dosen'; // Nama tabel di database
    protected $primaryKey = 'id'; // Primary key tabel
    protected $allowedFields = ['nidn', 'kode_dosen', 'nama_dosen', 'password', 'jenis_kelamin', 'alamat']; // Kolom yang dapat diisi
}
