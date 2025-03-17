<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model
{
    protected $table = 'akun';
    protected $allowedFields = ['nim', 'password', 'level'];
}
