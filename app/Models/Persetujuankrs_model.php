<?php

namespace App\Models;

use CodeIgniter\Model;

class Persetujuankrs_model extends Model
{
    protected $table = 'persetujuankrs';
    protected $primaryKey = 'id_persetujuan';
    protected $allowedFields = ['id_pengambilan', 'nim', 'status', 'keterangan', 'tanggal'];

    public function getPendingPersetujuan()
    {
        return $this->select('persetujuankrs.*, mahasiswa.nama')
                    ->join('mahasiswa', 'mahasiswa.nim = persetujuankrs.nim')
                    ->where('status', 'pending')
                    ->findAll();
    }
}
