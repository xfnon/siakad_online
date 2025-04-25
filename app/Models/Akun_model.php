<?php
namespace App\Models;

use CodeIgniter\Model;

class Akun_model extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id_akun';
    protected $allowedFields = ['nim', 'password', 'level'];
}
