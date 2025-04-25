<?php

namespace App\Models;

use CodeIgniter\Model;

class Fakultas_model extends Model
{
    protected $table = 'fakultas';
    protected $primaryKey = 'id_fakultas';
    protected $allowedFields = ['nama_fakultas'];
}

