<?php

namespace App\Models;

use CodeIgniter\Model;

class Akun_model extends Model
{
    protected $table = 'akun'; // Nama tabel di database
    protected $primaryKey = 'id_akun'; // Primary key tabel
    protected $allowedFields = ['id_akun', 'nim', 'password','level']; // Kolom yang dapat diisi
}
