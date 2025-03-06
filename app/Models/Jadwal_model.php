<?php

namespace App\Models;

use CodeIgniter\Model;

class Jadwal_model extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    protected $allowedFields = ['matkul', 'dosen', 'gedung', 'ruang', 'sks', 'hari', 'jam_mulai', 'jam_selesai', 'semester'];
}
