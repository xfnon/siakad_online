<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Mahasiswa_model;
use App\Models\Dosen_model;
use App\Models\Matkul_model;
use App\Models\Akun_model;
use App\Models\Jurusan_model;
use App\Models\Tahun_model;
use App\Models\Ruang_model;

class Admin extends BaseController
{

    //dashboard
    public function dashboard()
    {
        echo view('admin/dashboard');
    }




    //masterjurusan

    protected $jurusanModel;

    public function __construct()
    {
        $this->jurusanModel = new Jurusan_model();
    }

    public function jurusan()
    {
        $jurusanModel = new Jurusan_model();
        $data['jurusan'] = $jurusanModel->findAll();

        return view('admin/jurusan', $data);
    }

    public function savejurusan()
    {
        $model = new Jurusan_model();
        $data = array(
            'kode'  => $this->request->getPost('kode'),
            'nama' => $this->request->getPost('nama'),
        );
        $model->saveJurusan($data);
        return redirect()->to('admin/jurusan');
    }

    public function deletejurusan($id)
    {
        $this->jurusanModel->delete($id);
        return redirect()->to('/admin/jurusan');
    }




    //mastermahasiswa
    public function mahasiswa()
    {
        $jurusanModel = new Jurusan_model();
        $data['jurusan'] = $jurusanModel->findAll(); // Mengambil semua data dari tabel fakultas

        $model = new Mahasiswa_model();
        $data['mahasiswa'] = $model->getMahasiswa();
        echo view('admin/mahasiswa', $data);
    }

    public function savemhs()
    {
        $model = new Mahasiswa_model();
        $data = array(
            'nim'  => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'jk' => $this->request->getPost('jk'),
            'no_telp' => $this->request->getPost('no_telp'),
            'alamat' => $this->request->getPost('alamat'),
            'jurusan' => $this->request->getPost('jurusan'),
            'tahun' => $this->request->getPost('tahun'),
            'angkatan' => $this->request->getPost('angkatan'),
            'semester' => $this->request->getPost('semester'),
            'status' => $this->request->getPost('status') ?? 'belum',
        );
        $model->saveMahasiswa($data);
        return redirect()->to('admin/mahasiswa');
    }

    public function deletemhs($id)
    {
        $model = new Mahasiswa_model();
        $model->deleteMahasiswa($id);
        return redirect()->to('admin/mahasiswa');
    }





    //mastermatkul
    public function matkul()
    {

        $ruangModel = new Ruang_model();
        $data['ruang'] = $ruangModel->findAll(); // Mengambil semua data dari tabel fakultas

        $jurusanModel = new Jurusan_model();
        $data['jurusan'] = $jurusanModel->findAll(); // Mengambil semua data dari tabel fakultas

        $dosenModel = new Dosen_model();
        $data['dosen'] = $dosenModel->findAll(); // Mengambil semua data dari tabel fakultas

        $matkulModel = new Matkul_model();
        $data['matkul'] = $matkulModel->findAll(); // Mengambil semua data jadwal

        return view('admin/matkul', $data); // Menampilkan view daftar jadwal
    }

    public function savematkul()
    {
        $matkulModel = new Matkul_model();
        $matkulModel->save([
            'kode_matkul' => $this->request->getPost('kode_matkul'),
            'nama_matkul' => $this->request->getPost('nama_matkul'),
            'sks' => $this->request->getPost('sks'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'ruangan' => $this->request->getPost('ruangan'),
            'kode_dosen' => $this->request->getPost('kode_dosen'),
            'kode_jurusan' => $this->request->getPost('kode_jurusan'),
            'semester' => $this->request->getPost('semester'),
        ]);

        // Redirect to a success page or back to the form with a success message
        return redirect()->to('/admin/matkul')->with('success', 'Jadwal Mata Kuliah berhasil disimpan.');
    }

    public function editmatkul($id)
    {
        $matkulModel = new Matkul_model();
        $data['matkul'] = $matkulModel->find($id);

        if (!$data['matkul']) {
            return redirect()->to('/admin/matkul')->with('error', 'Mata kuliah tidak ditemukan.');
        }

        return view('admin/matkul', $data);
    }

    public function updatematkul()
    {
        $id = $this->request->getPost('id_matkul'); // Ambil ID dari form
        $data = [
            'kode_matkul' => $this->request->getPost('kode_matkul'),
            'nama_matkul' => $this->request->getPost('nama_matkul'),
            'sks' => $this->request->getPost('sks'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'ruangan' => $this->request->getPost('ruangan'),
            'kode_dosen' => $this->request->getPost('kode_dosen'),
        ];

        $model = new Matkul_model(); // Gantilah dengan nama model yang sesuai
        $model->update(
            $id,
            $data
        ); // Lakukan update berdasarkan ID

        return redirect()->to('/admin/matkul')->with('success', 'Data mata kuliah berhasil diperbarui');
    }

    public function deletematkul($id)
    {
        $model = new Matkul_model(); // Sesuaikan dengan model yang digunakan
        $matkul = $model->find($id);

        if ($matkul) {
            $model->delete($id);
            return redirect()->to('/admin/matkul')->with('success', 'Data mata kuliah berhasil dihapus');
        } else {
            return redirect()->to('/admin/matkul')->with('error', 'Data tidak ditemukan');
        }
    }


    //semester
    public function semester()
    {
        $model = new Mahasiswa_model();
        $semester = $this->request->getGet('semester');
        $jurusan = $this->request->getGet('jurusan');

        $data['mahasiswa'] = $model->getMahasiswa($semester, $jurusan);
        return view('admin/semester', $data);
    }

    public function dosen()
    {
        $dosenModel = new Dosen_model();
        $data['dosen'] = $dosenModel->findAll();

        return view('admin/dosen', $data);
    }

    public function updatedosen()
    {
        $dosenModel = new Dosen_model();

        // Ambil data dari form
        $id = $this->request->getPost('id');
        $data = [
            'nidn'          => $this->request->getPost('nidn'),
            'kode_dosen'    => $this->request->getPost('kode_dosen'),
            'nama_dosen'    => $this->request->getPost('nama_dosen'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat'        => $this->request->getPost('alamat'),
        ];

        // Update data dosen
        if ($dosenModel->update($id, $data)) {
            return redirect()->to('/admin/dosen')->with('success', 'Data dosen berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui data dosen.');
        }
    }
    public function deletedosen($id)
    {
        // Pastikan ID valid
        if (!$id) {
            return redirect()->to('/admin/dosen')->with('error', 'ID dosen tidak valid.');
        }

        // Cek apakah dosen ada di database
        $dosenModel = new \App\Models\Dosen_model();
        $dosen = $dosenModel->find($id);

        if (!$dosen) {
            return redirect()->to('/admin/dosen')->with('error', 'Dosen tidak ditemukan.');
        }

        // Hapus dosen dari database
        $dosenModel->delete($id);

        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil dihapus.');
    }


    public function akun()
    {
        $akunModel = new Akun_model();
        $data['akun'] = $akunModel->findAll();

        return view('admin/akun', $data);
    }

    public function tahun()
    {
        $tahunModel = new Tahun_model();
        $data['tahun'] = $tahunModel->findAll();

        return view('admin/tahun', $data);
    }

    public function ruang()
    {
        $ruangModel = new Ruang_model();
        $data['ruang'] = $ruangModel->findAll();

        return view('admin/ruang', $data);
    }























    public function savedosen()
    {
        // Validasi input
        $validation = $this->validate([
            'nidn' => 'required|numeric',
            'kode_dosen' => 'required',
            'nama_dosen' => 'required',
            'jenis_kelamin' => 'required|in_list[Laki-Laki,Perempuan]',
            'alamat' => 'required',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data ke database
        try {
            $dosenModel = new Dosen_model();
            $dosenModel->save([
                'nidn' => $this->request->getPost('nidn'),
                'kode_dosen' => $this->request->getPost('kode_dosen'),
                'nama_dosen' => $this->request->getPost('nama_dosen'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'alamat' => $this->request->getPost('alamat'),
            ]);

            return redirect()->to('/admin/dosen');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['database' => $e->getMessage()]);
        }
    }
}
