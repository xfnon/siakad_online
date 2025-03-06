<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mahasiswa_model;
use App\Models\Dosen_model;
use App\Models\Matkul_model;
use App\Models\Ruang_model;
use App\Models\Jadwal_model;

class Admin extends BaseController
{

    //dashboard
    public function dashboard()
    {
        echo view('admin/dashboard');
    }





    // MASTER DOSEN
    public function dosen()
    {
        $dosenModel = new Dosen_model();
        $data['dosen'] = $dosenModel->findAll();
        return view('admin/dosen', $data);
    }

    public function saveDosen()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nidn' => 'required|is_unique[dosen.nidn]',
            'kode_dosen' => 'required',
            'nama_dosen' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Simpan ke database
        $dosenModel = new \App\Models\Dosen_model();
        $dosenModel->insert([
            'nidn' => $this->request->getPost('nidn'),
            'kode_dosen' => $this->request->getPost('kode_dosen'),
            'nama_dosen' => $this->request->getPost('nama_dosen'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->to('/admin/dosen')->with('success', 'Data dosen berhasil ditambahkan.');
    }


    public function updatedosen()
    {
        if (!$this->validate([
            'id_dosen' => 'required',
            'nidn' => 'required|numeric',
            'kode_dosen' => 'required',
            'nama_dosen' => 'required',
            'jenis_kelamin' => 'required|in_list[Laki-Laki,Perempuan]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dosenModel = new Dosen_model();
        $id = $this->request->getPost('id_dosen');

        if (!$dosenModel->find($id)) {
            return redirect()->back()->with('error', 'Dosen tidak ditemukan.');
        }

        $dosenModel->update($id, [
            'nidn' => $this->request->getPost('nidn'),
            'kode_dosen' => $this->request->getPost('kode_dosen'),
            'nama_dosen' => $this->request->getPost('nama_dosen'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        ]);

        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function deletedosen($id)
    {
        $dosenModel = new Dosen_model();
        if (!$dosenModel->find($id)) {
            return redirect()->to('/admin/dosen')->with('error', 'Dosen tidak ditemukan.');
        }

        $dosenModel->delete($id);
        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil dihapus.');
    }

    public function ruang()
    {
        $ruangModel = new Ruang_model();
        $data['ruang'] = $ruangModel->findAll();

        return view('admin/ruang', $data);
    }

    public function saveRuang()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gedung' => 'required',
            'lantai' => 'required|numeric',
            'ruang'  => 'required|is_unique[ruang.ruang]',
            'kuota'  => 'required|numeric',
        ]);

        // Jika validasi gagal, kembali dengan error
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $ruangModel = new Ruang_model();
            $ruangModel->insert([
                'gedung'    => $this->request->getPost('gedung'),
                'lantai'    => $this->request->getPost('lantai'),
                'ruang'     => $this->request->getPost('ruang'),
                'kuota'     => $this->request->getPost('kuota'), // Gunakan "kuota" sesuai form
            ]);

            return redirect()->to('/admin/ruang')->with('success', 'Data ruang berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }




    public function updateRuang()
    {
        if (!$this->validate([
            'id_ruang' => 'required',
            'kode_ruang' => 'required',
            'ruang' => 'required',
            'gedung' => 'required',
            'kapasitas' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ruangModel = new Ruang_model();
        $id = $this->request->getPost('id_ruang');

        if (!$ruangModel->find($id)) {
            return redirect()->back()->with('error', 'Ruang tidak ditemukan.');
        }

        $ruangModel->update($id, [
            'kode_ruang' => $this->request->getPost('kode_ruang'),
            'ruang' => $this->request->getPost('ruang'),
            'gedung' => $this->request->getPost('gedung'),
            'kapasitas' => $this->request->getPost('kapasitas'),
        ]);

        return redirect()->to('/admin/ruang')->with('success', 'Ruang berhasil diperbarui.');
    }

    public function deleteRuang($id)
    {
        $ruangModel = new Ruang_model();

        // Pastikan data dengan ID ada sebelum dihapus
        if (!$ruangModel->find($id)) {
            return redirect()->to('/admin/ruang')->with('error', 'Ruang tidak ditemukan.');
        }

        // Hapus data
        $ruangModel->delete($id);
        return redirect()->to('/admin/ruang')->with('success', 'Ruang berhasil dihapus.');
    }

    //MASTER MATKUL


    public function matkul()
    {
        $matkulModel = new Matkul_model();
        $data['matkul'] = $matkulModel->findAll();

        $ruangModel = new Ruang_model();
        $dosenModel = new Dosen_model();
        $data['ruang'] = $ruangModel->findAll();
        $data['dosen'] = $dosenModel->findAll();

        return view('admin/matkul', $data);
    }

    public function saveMatkul()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kode_matkul' => 'required|is_unique[matkul.kode_matkul]',
            'matkul'      => 'required',
            'fakultas'    => 'required',
            'prodi'       => 'required',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $matkulModel = new Matkul_model();
            $matkulModel->insert([
                'kode_matkul' => $this->request->getPost('kode_matkul'),
                'matkul'      => $this->request->getPost('matkul'),
                'fakultas'    => $this->request->getPost('fakultas'),
                'prodi'       => $this->request->getPost('prodi'),
            ]);

            return redirect()->to('/admin/matkul')->with('success', 'Mata kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan, coba lagi.');
        }
    }

    public function editMatkul()
    {
        $matkulModel = new Matkul_model();

        $id = $this->request->getPost('id_matkul');

        $data = [
            'kode_matkul' => $this->request->getPost('kode_matkul'),
            'matkul'      => $this->request->getPost('matkul'),
            'fakultas'    => $this->request->getPost('fakultas'),
            'prodi'       => $this->request->getPost('prodi'),
        ];

        try {
            $matkulModel->update($id, $data);
            return redirect()->to('/admin/matkul')->with('success', 'Mata kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/matkul')->with('error', 'Gagal memperbarui mata kuliah.');
        }
    }


    public function deleteMatkul($id)
    {
        $matkulModel = new Matkul_model();

        try {
            // Cek apakah data ada
            $matkul = $matkulModel->find($id);
            if (!$matkul) {
                return redirect()->to('/admin/matkul')->with('error', 'Mata kuliah tidak ditemukan.');
            }

            // Hapus data
            $matkulModel->delete($id);
            return redirect()->to('/admin/matkul')->with('success', 'Mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/matkul')->with('error', 'Gagal menghapus mata kuliah.');
        }
    }

    //SETUPJADWAL
    public function setupjadwal()
    {
        $matkulModel = new Matkul_model();
        $data['matkul'] = $matkulModel->findAll();

        $dosenModel = new Dosen_model();
        $data['dosen'] = $dosenModel->findAll();

        $ruangModel = new Ruang_model();
        $data['ruang'] = $ruangModel->findAll();

        $jadwalModel = new Jadwal_model();
        $data['jadwal'] = $jadwalModel->findAll();
        return view('admin/setupjadwal', $data);
    }
    public function savejadwal()
    {
        if (!$this->validate([
            'matkul' => 'required',
            'dosen' => 'required',
            'gedung' => 'required',
            'ruang' => 'required',
            'sks' => 'required|numeric',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'semester' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jadwalModel = new Jadwal_model();

        $jadwalModel->insert([
            'matkul' => $this->request->getPost('matkul'),
            'dosen' => $this->request->getPost('dosen'),
            'gedung' => $this->request->getPost('gedung'),
            'ruang' => $this->request->getPost('ruang'),
            'sks' => $this->request->getPost('sks'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'semester' => $this->request->getPost('semester'),
        ]);

        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal berhasil disimpan.');
    }
}
