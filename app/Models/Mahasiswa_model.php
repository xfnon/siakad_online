<?php

namespace App\Models;

use CodeIgniter\Model;

class Mahasiswa_model extends Model
{
    protected $table = 'mahasiswa'; // Ganti dengan nama tabel Anda
    protected $primaryKey = 'id_mhs'; // Ganti dengan primary key Anda
    protected $allowedFields = ['nim', 'nama', 'jk', 'no_telp', 'alamat', 'prodi', 'fakultas', 'tahun', 'angkatan']; // Pastikan field ini sesuai dengan tabel Anda


    public function getMahasiswa($semester = null, $jurusan = null)
    {
        $builder = $this->db->table($this->table);

        if ($semester) {
            $builder->where('semester', $semester);
        }
        if ($jurusan) {
            $builder->where('jurusan', $jurusan);
        }

        return $builder->get()->getResultArray();
    }

    public function saveMahasiswa($data)
    {
        return $this->db->table('mahasiswa')->insert($data);
    }

    public function deleteMahasiswa($id)
    {
        return $this->db->table('mahasiswa')->delete(['id_mhs' => $id]);
    }

    public function updateMahasiswa($data, $id)
    {
        $query = $this->db->table($this->table)->update($data, array('id_mhs' => $id));
        return $query;
    }
}
