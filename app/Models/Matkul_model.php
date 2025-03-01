<?php

namespace App\Models;

use CodeIgniter\Model;

class Matkul_model extends Model
{
    protected $table = 'matkul'; // Nama tabel
    protected $primaryKey = 'id_matkul';
    protected $allowedFields = ['kode_matkul', 'nama_matkul', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'id_dosen'];

    
}