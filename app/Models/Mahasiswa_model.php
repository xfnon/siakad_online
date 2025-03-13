<?php

namespace App\Models;

use CodeIgniter\Model;

class Mahasiswa_model extends Model
{
    protected $table = 'mahasiswa'; // Nama tabel di database
    protected $primaryKey = 'id_mahasiswa'; // Primary key tabel
    protected $allowedFields = ['id_mahasiswa', 'nim', 'nama', 'jk', 'no_telp', 'alamat', 'prodi', 'angkatan', 'semester']; // Kolom yang dapat diisi
}
