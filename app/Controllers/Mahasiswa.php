<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mahasiswa_model;
use App\Models\Matkul_model;
use App\Models\Dosen_model;
use App\Models\Ruang_model;
use App\Models\Jadwal_model; 

class Mahasiswa extends BaseController
{
    // Dashboard
    public function dashboard()
    {
        echo view('mahasiswa/dashboard');
    }

    public function rencanastudi()
    {
        $ruangModel  = new Ruang_model();
        $dosenModel  = new Dosen_model();
        $matkulModel = new Matkul_model();
        $jadwalModel = new Jadwal_model(); // Panggil model jadwal

        $data['ruang']  = $ruangModel->findAll();
        $data['dosen']  = $dosenModel->findAll();
        $data['matkul'] = $matkulModel->findAll();
        $data['jadwal'] = $jadwalModel->findAll(); // Kirim data jadwal ke view

        return view('mahasiswa/rencanastudi', $data);
    }


    public function jadwal()
    {
        $session = session(); // ambil session service
        $nim = $session->get('nim'); // pastikan session menyimpan nim
    
        $pengambilanModel = new \App\Models\Pengambilan_model();
        $data['jadwal'] = $pengambilanModel->getJadwalDisetujui($nim);
    
        return view('mahasiswa/jadwal', $data);
    }
    
    

    public function hasilstudi()
    {
        echo view('mahasiswa/hasilstudi');
    }

    public function transkripnilai()
    {
        echo view('mahasiswa/transkripnilai');
    }

    public function panduankrs()
    {
        echo view('mahasiswa/panduankrs');
    }

    public function datamhs()
    {
        $model = new Mahasiswa_model();
        $data['mahasiswa'] = $model->getMahasiswa();
        echo view('mahasiswa/datamhs', $data);
    }

    public function save()
    {
        $model = new Mahasiswa_model();
        $data = [
            'nim'       => $this->request->getPost('nim'),
            'nama'      => $this->request->getPost('nama'),
            'jk'        => $this->request->getPost('jk'),
            'no_telp'   => $this->request->getPost('no_telp'),
            'alamat'    => $this->request->getPost('alamat'),
            'prodi'     => $this->request->getPost('prodi'),
            'fakultas'  => $this->request->getPost('fakultas'),
            'tahun'     => $this->request->getPost('tahun'),
            'angkatan'  => $this->request->getPost('angkatan'),
        ];
        $model->saveMahasiswa($data);
        return redirect()->to('admin/mahasiswa');
    }

    public function delete($id)
    {
        $model = new Mahasiswa_model();
        $model->deleteMahasiswa($id);
        return redirect()->to('mahasiswa/datamhs');
    }

    public function edit($id)
    {
        $model = new Mahasiswa_model();
        $data['mahasiswa'] = $model->getMahasiswa($id)->getRow();
        echo view('mahasiswa/edit', $data);
    }

    public function update()
    {
        $model = new Mahasiswa_model();
        $id = $this->request->getPost('id_mhs');
        $data = [
            'nim'       => $this->request->getPost('nim'),
            'nama'      => $this->request->getPost('nama'),
            'jk'        => $this->request->getPost('jk'),
            'no_telp'   => $this->request->getPost('no_telp'),
            'alamat'    => $this->request->getPost('alamat'),
            'prodi'     => $this->request->getPost('prodi'),
            'fakultas'  => $this->request->getPost('fakultas'),
            'tahun'     => $this->request->getPost('tahun'),
            'angkatan'  => $this->request->getPost('angkatan'),
        ];
        $model->updateMahasiswa($data, $id);
        return redirect()->to('mahasiswa/datamhs');
    }

    public function blank()
    {
        echo view('blank');
    }

    public function konfirmasiKrs()
    {
        $session = session();
        $nim = $session->get('nim');
    
        if (!$nim) {
            return redirect()->to('mahasiswa/rencanastudi')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        $selected = $this->request->getPost('id_sks');
    
        if ($selected && is_array($selected)) {
            $db = \Config\Database::connect();
            $builder = $db->table('pengambilan');
    
            foreach ($selected as $id_sks) {
                $sks = $this->request->getPost("sks_$id_sks");
                $matkul = $this->request->getPost("matkul_$id_sks");
    
                $data = [
                    'nim' => $nim,
                    'id_sks' => $id_sks,
                    'status' => 'pending', // Set status jadi 'pending'
                    'sks' => $sks,
                    'matkul' => $matkul
                ];
    
                $builder->insert($data);
            }
    
            return redirect()->to('mahasiswa/rencanastudi')->with('success', 'KRS berhasil diajukan.');
        } else {
            return redirect()->to('mahasiswa/rencanastudi')->with('error', 'Tidak ada mata kuliah yang dipilih.');
        }
    }
    
    // Method untuk menampilkan pengajuan KRS yang pending
    public function konfirmasi()
    {
        $session = session();
        $nim = $session->get('nim');
    
        if (!$nim) {
            return redirect()->to('mahasiswa/rencanastudi')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        $db = \Config\Database::connect();
        $builder = $db->table('pengambilan');
    
        // Mengambil data mahasiswa yang mengajukan KRS dengan status 'pending'
        $builder->select('mahasiswa.nama, mahasiswa.nim, prodi.kode_prodi, prodi.nama_prodi');
        $builder->join('mahasiswa', 'mahasiswa.nim = pengambilan.nim');
        $builder->join('prodi', 'prodi.id_prodi = mahasiswa.id_prodi');
        $builder->where('pengambilan.status', 'pending'); // Pastikan statusnya 'pending'
        $builder->groupBy('mahasiswa.nim'); // Mengelompokkan berdasarkan nim mahasiswa untuk mencegah duplikasi
    
        $query = $builder->get();
        $data['mahasiswa'] = $query->getResultArray(); // <-- Data mahasiswa yang mengajukan KRS
    
        // Cek apakah ada data mahasiswa yang mengajukan KRS
        if (empty($data['mahasiswa'])) {
            $data['message'] = 'Belum ada pengajuan KRS dari mahasiswa.';
        }
    
        return view('dosen/konfirmasi', $data);
    }

    public function penilaianMatkul($id_matkul)
    {
        $session = session();
        $nim = $session->get('nim'); // Pastikan mengambil NIM dosen yang login
        
        if (!$nim) {
            return redirect()->to('mahasiswa/rencanastudi')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        // Ambil data dosen berdasarkan NIM dosen yang ada di session
        $dosenModel = new Dosen_model();
        $dosen = $dosenModel->where('nim', $nim)->first();  // Misalnya menggunakan NIM dosen yang ada di session
    
        // Jika dosen tidak ditemukan
        if (!$dosen) {
            return redirect()->to('dosen/dashboard')->with('error', 'Dosen tidak ditemukan.');
        }
    
        // Ambil data mahasiswa yang mengajukan KRS dengan mata kuliah yang dosen ajarkan
        $pengambilanModel = new \App\Models\Pengambilan_model();
        $data['mahasiswa'] = $pengambilanModel->getMahasiswaDiampu($dosen['id_dosen'], $id_matkul); // Mengambil mahasiswa yang mengambil matkul dosen
    
        // Kirim data dosen dan mahasiswa ke view
        $data['id_matkul'] = $id_matkul;
        $data['dosen'] = $dosen;
        
        return view('dosen/penilaian', $data); // Menampilkan halaman penilaian
    }
    

    
}



    
    

