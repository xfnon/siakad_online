<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mahasiswa_model;
use App\Models\Dosen_model;
use App\Models\Matkul_model;
use App\Models\Ruang_model;
use App\Models\Prodi_model;
use App\Models\Fakultas_model;
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



    //MASTER RUANG
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


    //MASTER PRODI
    public function prodi()
    {
        $prodiModel = new Prodi_model();
        $fakultasModel = new Fakultas_model(); // Ambil daftar fakultas untuk dropdown

        $data['prodi'] = $prodiModel->orderBy('id_prodi', 'ASC')->findAll();
        $data['fakultas'] = $fakultasModel->orderBy('id_fakultas', 'ASC')->findAll();

        return view('admin/prodi', $data);
    }

    public function saveProdi()
    {
        $prodiModel = new Prodi_model();

        $prodiModel->insert([
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'id_fakultas' => $this->request->getPost('id_fakultas'),
        ]);

        return redirect()->to('/admin/prodi')->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function editProdi()
    {
        $prodiModel = new Prodi_model();
        $id = $this->request->getPost('id_prodi');

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_prodi' => 'required|max_length[255]',
            'id_fakultas' => 'required|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Update data ke database
        $prodiModel->update($id, [
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'id_fakultas' => $this->request->getPost('id_fakultas'),
        ]);

        return redirect()->to('/admin/prodi')->with('success', '.');
    }

    public function deleteProdi($id)
    {
        $prodiModel = new Prodi_model();
        $prodiModel->delete($id);

        return redirect()->to('/admin/prodi')->with('success', '.');
    }



    //MASTER FAKULTAS
    public function fakultas()
    {

        $fakultasModel = new Fakultas_model();
        $data['fakultas'] = $fakultasModel->orderBy('id_fakultas', 'ASC')->findAll();

        return view('admin/fakultas', $data);
    }

    public function saveFakultas()
    {
        $fakultasModel = new Fakultas_model();

        $fakultasModel->insert([
            'nama_fakultas' => $this->request->getPost('nama_fakultas'),
        ]);

        return redirect()->to('/admin/fakultas')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function editFakultas()
    {
        $fakultasModel = new Fakultas_model();
        $id = $this->request->getPost('id_fakultas');

        $fakultasModel->update($id, [
            'nama_fakultas' => $this->request->getPost('nama_fakultas'),
        ]);

        return redirect()->to('/admin/fakultas')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function deleteFakultas($id)
    {
        $fakultasModel = new Fakultas_model();
        $fakultasModel->delete($id);

        return redirect()->to('/admin/fakultas')->with('success', 'Fakultas berhasil dihapus.');
    }



    //SETUP JADWAL
    public function setupjadwal()
    {
        $dosenModel = new Dosen_model();
        $data['dosen'] = $dosenModel->findAll();

        $ruangModel = new Ruang_model();
        $data['ruang'] = $ruangModel->findAll();

        $matkulModel = new Matkul_model();
        $data['matkul'] = $matkulModel->findAll();

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
