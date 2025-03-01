<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mahasiswa_model;
use App\Models\Matkul_model;
use App\Models\Dosen_model;
use App\Models\Ruang_model;


class Mahasiswa extends BaseController
{

    //dashboard
    public function dashboard()
    {
        echo view('mahasiswa/dashboard');
    }

    public function rencanastudi()
    {
        $ruangModel = new Ruang_model();
        $data['ruang'] = $ruangModel->findAll(); // Mengambil semua data dari tabel fakultas

        $dosenModel = new Dosen_model();
        $data['dosen'] = $dosenModel->findAll(); // Mengambil semua data dari tabel fakultas

        $matkulModel = new Matkul_model();
        $data['matkul'] = $matkulModel->findAll(); // Mengambil semua data jadwal

        return view('mahasiswa/rencanastudi', $data); // Menampilkan view daftar jadwal	}
    }

    public function kelas()
    {
        echo view('mahasiswa/kelas');
    }

    public function jadwal()
    {
        echo view('mahasiswa/jadwal');
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
        $data = array(
            'nim'  => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'jk' => $this->request->getPost('jk'),
            'no_telp' => $this->request->getPost('no_telp'),
            'alamat' => $this->request->getPost('alamat'),
            'prodi' => $this->request->getPost('prodi'),
            'fakultas' => $this->request->getPost('fakultas'),
            'tahun' => $this->request->getPost('tahun'),
            'angkatan' => $this->request->getPost('angkatan'),
        );
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
        $data = array(
            'nim'  => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'jk' => $this->request->getPost('jk'),
            'no_telp' => $this->request->getPost('no_telp'),
            'alamat' => $this->request->getPost('alamat'),
            'prodi' => $this->request->getPost('prodi'),
            'fakultas' => $this->request->getPost('fakultas'),
            'tahun' => $this->request->getPost('tahun'),
            'angkatan' => $this->request->getPost('angkatan'),
        );
        $model->updateMahasiswa($data, $id);
        return redirect()->to('mahasiswa/datamhs');
    }


    public function blank()
    {
        echo view('blank');
    }
}
