<?php

namespace App\Models;

use CodeIgniter\Model;

class Penilaian_model extends Model
{
    protected $table = 'pengambilan';
    protected $primaryKey = 'id_pengambilan';
    protected $allowedFields = ['nim', 'id_sks', 'status', 'id_jadwal', 'tanggal_pengajuan', 'sks', 'matkul'];

    // Ambil daftar mahasiswa yang mengambil matkul tertentu
    public function getMahasiswaDiampu($id_dosen, $id_matkul)
    {
        return $this->db->table('pengambilan')
            ->select('mahasiswa.nim, mahasiswa.nama as nama_mahasiswa, pengambilan.id_pengambilan, jadwal.dosen')
            ->join('mahasiswa', 'mahasiswa.nim = pengambilan.nim')
            ->join('jadwal', 'jadwal.id_jadwal = pengambilan.id_jadwal') // Join dengan tabel jadwal untuk mendapatkan dosen
            ->where('pengambilan.matkul', $id_matkul)
            ->where('pengambilan.status', 'disetujui') // Mengambil mahasiswa yang sudah disetujui
            ->where('jadwal.dosen', $id_dosen) // Mengambil dosen berdasarkan id_dosen
            ->get()->getResult(); // Menampilkan hasil
    }
    


    
}
