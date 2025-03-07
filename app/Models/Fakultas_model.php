<?php

namespace App\Models;

use CodeIgniter\Model;

class Fakultas_model extends Model
{
    protected $table = 'fakultas';  // Nama tabel di database
    protected $primaryKey = 'id_fakultas'; // Primary key tabel
    protected $allowedFields = ['nama_fakultas']; // Kolom yang boleh diisi
}

