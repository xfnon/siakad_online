<?php

namespace App\Controllers;

use App\Models\Pengambilan_model;
use App\Models\Penilaian_model;
use App\Models\Dosen_model;

class Dosen extends BaseController
{
    public function dashboard()
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];

        return view('dosen/dashboard', $data);
    }



    public function persetujuan()
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];

        $model = new \App\Models\Pengambilan_model(); // Pastikan model ini ada
        $pengajuan = $model->getPengajuanPending(); // Method ini ambil data pengajuan

        return view('dosen/persetujuan', $data, [
            'pengajuan' => $pengajuan // Pastikan ini dikirim ke view
        ]);
    }




    public function konfirmasi($nim)
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];

        $db = \Config\Database::connect();

        // Ambil data mahasiswa
        $mahasiswa = $db->table('mahasiswa')->where('nim', $nim)->get()->getRowArray();

        // Ambil data pengambilan dan JOIN dengan tabel jadwal
        $pengambilan = $db->table('pengambilan')
            ->select('pengambilan.*, 
                      jadwal.matkul, 
                      jadwal.sks, 
                      jadwal.dosen AS nama_dosen, 
                      jadwal.hari, 
                      jadwal.jam_mulai, 
                      jadwal.jam_selesai, 
                      jadwal.semester')
            ->join('jadwal', 'pengambilan.id_sks = jadwal.id_jadwal') // Ganti jadwal jika nama tabel beda
            ->where('pengambilan.nim', $nim)
            ->where('pengambilan.status', 'pending')
            ->get()
            ->getResultArray();

        return view('dosen/konfirmasi', $data, [
            'mahasiswa'   => $mahasiswa,
            'pengambilan' => $pengambilan
        ]);
    }




    public function absensi()
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];

        return view('dosen/absensi', $data);
    }


    // Method untuk menyetujui KRS
    public function setujuiKrs($nim)
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];

        $db = \Config\Database::connect();

        // Ambil data pengambilan yang statusnya masih pending
        $pengambilan = $db->table('pengambilan')
            ->where('nim', $nim)
            ->where('status', 'pending')
            ->get()
            ->getResultArray();

        // Update semua menjadi disetujui dan set id_jadwal
        foreach ($pengambilan as $ambil) {
            $db->table('pengambilan')
                ->where('id_pengambilan', $ambil['id_pengambilan'])
                ->update([
                    'status'    => 'disetujui',
                    'id_jadwal' => $ambil['id_sks'] // langsung pakai id_sks
                ]);
        }

        return redirect()->to('dosen/persetujuan')->with('success', 'Pengajuan KRS telah disetujui.');
    }





    // Method untuk menolak KRS
    public function tolakKrs($nim)
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];


        $db = \Config\Database::connect();
        $builder = $db->table('pengambilan');
        $builder->set('status', 'ditolak');
        $builder->where('nim', $nim); // NIM mahasiswa
        $builder->update();

        return redirect()->to('dosen/persetujuan')->with('error', 'Pengajuan KRS telah ditolak.');
    }



    protected $dosenModel;
    protected $penilaianModel;

    public function __construct()
    {
        $this->dosenModel = new Dosen_model();
        $this->penilaianModel = new Penilaian_model();
        helper('url');
        session();
    }

    public function penilaian($id_matkul = null)
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            $nim = $session->get('nim'),
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];


        // Cek apakah dosen sudah login
        if (!$nim) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data dosen berdasarkan NIDN (yang disimpan di session sebagai 'nim')
        $dosen = $this->dosenModel->where('nidn', $nim)->first();

        // Jika data dosen tidak ditemukan
        if (!$dosen) {
            return redirect()->to('/dosen/dashboard')->with('error', 'Dosen tidak ditemukan.');
        }

        $id_dosen = $dosen['id_dosen'];

        // Jika tidak ada matkul dipilih, ambil dari field 'matkul_diampu'
        if ($id_matkul === null) {
            $id_matkul = $dosen['matkul_diampu'] ?? null;

            if (!$id_matkul) {
                return redirect()->to('/dosen/dashboard')->with('error', 'Matkul yang diampu tidak ditemukan.');
            }
        }

        // Ambil data mahasiswa yang mengambil matkul tersebut
        $data['mahasiswa'] = $this->penilaianModel->getMahasiswaDiampu($id_dosen, $id_matkul);
        $data['id_matkul'] = $id_matkul;
        $data['nama_dosen'] = $dosen['nama_dosen']; // Pastikan nama kolom sesuai dengan struktur tabel

        // Kirim data mahasiswa ke view
        return view('dosen/penilaian', $data);
    }





    public function nilaiMahasiswa()
    {
        $session = session();
        $dosen_nim = $session->get('nim'); // Mendapatkan nim dosen yang login

        // Ambil data dosen berdasarkan nim
        $dosen = (new \App\Models\Dosen_model())->where('nim', $dosen_nim)->first();
        if (!$dosen) {
            return redirect()->to('/login')->with('error', 'Dosen tidak ditemukan');
        }

        // Ambil data mahasiswa yang mengikuti mata kuliah yang diampu dosen
        $pengambilanModel = new \App\Models\Pengambilan_model();
        $data['mahasiswa'] = $pengambilanModel->getMahasiswaByDosen($dosen_nim); // Ambil semua mahasiswa yang mengikuti mata kuliah dosen

        return view('dosen/penilaian', $data); // Menampilkan data mahasiswa yang bisa dinilai
    }






    public function simpan_nilai()
    {
        $db = \Config\Database::connect();

        $nilai = $this->request->getPost('nilai'); // Nilai mahasiswa
        $kehadiran = $this->request->getPost('kehadiran'); // Kehadiran mahasiswa
        $id_pengambilan = $this->request->getPost('id_pengambilan'); // ID pengambilan
        $id_matkul = $this->request->getPost('id_matkul'); // ID mata kuliah

        foreach ($id_pengambilan as $i => $pid) {
            $builder = $db->table('nilai');
            $existing = $builder->where('id_pengambilan', $pid)->get()->getRow(); // Cek apakah sudah ada nilai untuk pengambilan tersebut

            $data = [
                'id_pengambilan' => $pid,
                'kehadiran' => $kehadiran[$i],
                'nilai' => $nilai[$i],
                'id_matkul' => $id_matkul // Menyimpan ID matkul yang dipilih
            ];

            if ($existing) {
                // Jika nilai sudah ada, update
                $builder->where('id_pengambilan', $pid)->update($data);
            } else {
                // Jika nilai belum ada, insert
                $builder->insert($data);
            }
        }

        return redirect()->to('/dosen/penilaian/' . $id_matkul)->with('success', 'Nilai berhasil disimpan!');
    }
}
