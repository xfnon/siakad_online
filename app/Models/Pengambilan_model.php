<?php

namespace App\Models;
use CodeIgniter\Model;

class Pengambilan_model extends Model
{
    protected $table = 'pengambilan';
    protected $primaryKey = 'id_pengambilan';
    protected $allowedFields = ['nim', 'id_sks', 'status', 'sks', 'matkul', 'id_jadwal'];

    // Ambil data mahasiswa yang mengambil matkul tertentu (via jadwal)
    public function getMahasiswaByIdMatkul($id_matkul)
    {
        return $this->db->table('pengambilan')
            ->select('pengambilan.id_pengambilan, pengambilan.nim, mahasiswa.nama, nilai.kehadiran, nilai.nilai, nilai.grade, nilai.lulus')
            ->join('mahasiswa', 'mahasiswa.nim = pengambilan.nim')
            ->join('jadwal', 'jadwal.id_jadwal = pengambilan.id_jadwal')
            ->join('nilai', 'nilai.id_pengambilan = pengambilan.id_pengambilan', 'left')
            ->where('jadwal.id_matkul', $id_matkul)
            ->groupBy('pengambilan.nim')
            ->get()
            ->getResultArray();
    }

    // Ambil satu pengajuan (distinct per NIM) dengan status pending
    public function getPengajuanPending()
    {
        return $this->db->table($this->table)
            ->select('nim, status')
            ->where('status', 'pending')
            ->groupBy('nim')
            ->get()
            ->getResultArray();
    }

    // Ambil semua detail pengambilan berdasarkan NIM
    public function getDetailPengambilanByNIM($nim)
    {
        return $this->where('nim', $nim)->findAll();
    }

    // Ambil jadwal SKS yang sudah disetujui dosen berdasarkan NIM
    public function getJadwalDisetujui($nim)
    {
        return $this->db->table('pengambilan AS p')
            ->join('jadwal AS j', 'j.id_jadwal = p.id_jadwal')
            ->select('j.hari, j.jam_mulai, j.jam_selesai, j.matkul, j.dosen, j.gedung, j.ruang, j.semester, p.sks')
            ->where('p.nim', $nim)
            ->where('p.status', 'disetujui')
            ->orderBy('j.hari', 'ASC')
            ->orderBy('j.jam_mulai', 'ASC')
            ->get()
            ->getResult();
    }

    public function getMahasiswaDiampu($id_dosen, $id_matkul)
{
    return $this->db->table('pengambilan')
        ->select('pengambilan.id_pengambilan, pengambilan.nim, mahasiswa.nama, nilai.kehadiran, nilai.nilai')
        ->join('mahasiswa', 'mahasiswa.nim = pengambilan.nim')
        ->join('jadwal', 'jadwal.id_jadwal = pengambilan.id_jadwal')
        ->join('nilai', 'nilai.id_pengambilan = pengambilan.id_pengambilan', 'left')
        ->where('jadwal.id_matkul', $id_matkul)
        ->where('jadwal.dosen', $id_dosen)
        ->where('pengambilan.status', 'disetujui') // hanya yang sudah acc
        ->get()
        ->getResultArray();
}

}
