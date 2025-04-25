<?php

namespace App\Models;

use CodeIgniter\Model;

class Jadwal_model extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    protected $allowedFields = [
        'matkul', 'dosen', 'gedung', 'ruang', 'sks',
        'hari', 'jam_mulai', 'jam_selesai', 'semester'
    ];

    // Ambil semua data jadwal (untuk admin)
    public function getAllJadwal()
    {
        return $this->select('jadwal.*, matkul.matkul as nama_matkul, dosen.nama_dosen, ruang.gedung, ruang.ruang as nama_ruang')
                    ->join('matkul', 'matkul.id_matkul = jadwal.matkul')
                    ->join('dosen', 'dosen.id_dosen = jadwal.dosen')
                    ->join('ruang', 'ruang.id_ruang = jadwal.ruang')
                    ->orderBy('hari', 'ASC')
                    ->orderBy('jam_mulai', 'ASC')
                    ->findAll();
    }

    // Ambil jadwal yang sudah di-ACC dosen berdasarkan NIM mahasiswa
    public function getJadwalByMahasiswa($nim)
    {
        return $this->db->table('pengambilan')
            ->select('
                matkul.matkul as nama_matkul,
                dosen.nama_dosen,
                ruang.gedung,
                ruang.ruang as nama_ruang,
                jadwal.hari,
                jadwal.jam_mulai,
                jadwal.jam_selesai,
                jadwal.semester
            ')
            ->join('jadwal', 'jadwal.id_jadwal = pengambilan.id_jadwal')
            ->join('matkul', 'matkul.id_matkul = jadwal.matkul')
            ->join('dosen', 'dosen.id_dosen = jadwal.dosen')
            ->join('ruang', 'ruang.id_ruang = jadwal.ruang')
            ->where('pengambilan.nim', $nim)
            ->where('pengambilan.status', 'disetujui')
            ->get()
            ->getResultArray();
    }
}
