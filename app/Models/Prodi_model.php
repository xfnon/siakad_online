<?php

namespace App\Models;

use CodeIgniter\Model;

class Prodi_model extends Model
{
    protected $table = 'prodi'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'id_prodi';
    protected $allowedFields = ['nama_prodi', 'id_fakultas'];
}
