<?php

namespace App\Models;

use CodeIgniter\Model;

class Tahun_model extends Model
{
    protected $table = 'tahunajaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tahun', 'semester', 'tanggalmulai', 'tanggalselesai', 'status'];
}
