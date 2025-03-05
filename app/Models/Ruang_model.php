<?php

namespace App\Models;

use CodeIgniter\Model;

class Ruang_model extends Model
{
    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';
    protected $allowedFields = ['gedung', 'lantai', 'ruang', 'kuota'];
}
