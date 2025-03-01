<?php

namespace App\Models;

use CodeIgniter\Model;

class Ruang_model extends Model
{
    protected $table = 'ruang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_ruang', 'nama_ruang', 'kapasitas', 'jurusan'];
}