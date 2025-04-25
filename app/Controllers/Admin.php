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
        $session = session();

        // Kirim data session ke view
        $data = [
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];
        return view('admin/dashboard', $data);
    }

    //MASTER MAHASISWA 
public function mahasiswa()
{
    $session = session();

    // Kirim data session ke view
    $data = [
        'nim'   => $session->get('nim'),
        'level' => $session->get('level')
    ];

    $mahasiswaModel = new Mahasiswa_model();
    $prodiModel = new \App\Models\Prodi_model(); // Pastikan model ini sudah dibuat

    $data['mahasiswa'] = $mahasiswaModel->findAll();
    $data['prodi'] = $prodiModel->findAll(); // Ambil semua data prodi untuk dropdown

    return view('admin/mahasiswa', $data);
}

    public function saveMahasiswa()
{
    $nim = $this->request->getPost('nim');
    $nama = $this->request->getPost('nama');
    $jk = $this->request->getPost('jk');
    $no_telp = $this->request->getPost('no_telp');
    $alamat = $this->request->getPost('alamat');
    $prodi = $this->request->getPost('prodi');
    $angkatan = $this->request->getPost('angkatan');
    $semester = $this->request->getPost('semester');
    $password = $this->request->getPost('password');

    $mahasiswaModel = new \App\Models\Mahasiswa_model();
    $akunModel = new \App\Models\Akun_model();

    // Simpan ke tabel mahasiswa
    $mahasiswaModel->insert([
        'nim'       => $nim,
        'nama'      => $nama,
        'jk'        => $jk,
        'no_telp'   => $no_telp,
        'alamat'    => $alamat,
        'prodi'     => $prodi,
        'angkatan'  => $angkatan,
        'semester'  => $semester,
    ]);

    // Simpan ke tabel akun
    $akunModel->insert([
        'nim'       => $nim,
        'password'  => password_hash($password, PASSWORD_DEFAULT),
        'level'     => 'mahasiswa'
    ]);

    return redirect()->to('/admin/mahasiswa')->with('success', 'Data mahasiswa dan akun berhasil ditambahkan.');
}

    

    public function updateMahasiswa()
    {
        if (!$this->validate([
            'id_mahasiswa' => 'required|numeric',
            'nim' => 'required|numeric',
            'nama' => 'required',
            'jk' => 'required|in_list[Laki-Laki,Perempuan]',
            'no_telp' => 'required|numeric',
            'alamat' => 'required',
            'prodi' => 'required',
            'angkatan' => 'required|numeric',
            'semester' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $mahasiswaModel = new Mahasiswa_model();
        $id = $this->request->getPost('id_mahasiswa');

        if (!$mahasiswaModel->find($id)) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $mahasiswaModel->update($id, [
            'nim' => $this->request->getPost('nim'),
            'nama' => $this->request->getPost('nama'),
            'jk' => $this->request->getPost('jk'),
            'no_telp' => $this->request->getPost('no_telp'),
            'alamat' => $this->request->getPost('alamat'),
            'prodi' => $this->request->getPost('prodi'),
            'angkatan' => $this->request->getPost('angkatan'),
            'semester' => $this->request->getPost('semester'),
        ]);

        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function deleteMahasiswa($id)
    {
        $mahasiswaModel = new Mahasiswa_model();

        if (!$mahasiswaModel->find($id)) {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $mahasiswaModel->delete($id);
        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus.');
    }




    // MASTER DOSEN
    public function dosen()
    {
        $session = session();
    
        // Kirim data session ke view
        $data = [
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];
    
        $dosenModel = new Dosen_model();
        $matkulModel = new \App\Models\Matkul_model(); // Tambahkan model matkul
        
        $data['dosen'] = $dosenModel->findAll();
        $data['matkul'] = $matkulModel->findAll(); // Kirim data matkul ke view
    
        return view('admin/dosen', $data);
    }
    

    public function saveDosen()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nidn' => 'required|is_unique[dosen.nidn]|is_unique[akun.nim]',
            'kode_dosen' => 'required',
            'nama_dosen' => 'required',
            'jenis_kelamin' => 'required',
            'password' => 'required|min_length[6]',
            'matkul' => 'required' // input: array of matkul id
        ]);
    
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    
        $nidn          = $this->request->getPost('nidn');
        $kode_dosen    = $this->request->getPost('kode_dosen');
        $nama_dosen    = $this->request->getPost('nama_dosen');
        $jenis_kelamin = $this->request->getPost('jenis_kelamin');
        $password      = $this->request->getPost('password');
        $matkul_ids    = $this->request->getPost('matkul'); // array of id_matkul
    
        $dosenModel = new \App\Models\Dosen_model();
        $akunModel  = new \App\Models\Akun_model();
        $db         = \Config\Database::connect();
    
        // Transaksi untuk menghindari separuh data tersimpan
        $db->transStart();
    
        $dosenModel->insert([
            'nidn'           => $nidn,
            'kode_dosen'     => $kode_dosen,
            'nama_dosen'     => $nama_dosen,
            'jenis_kelamin'  => $jenis_kelamin,
        ]);
        $id_dosen = $dosenModel->getInsertID();
    
        $akunModel->insert([
            'nim'      => $nidn,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'level'    => 'dosen'
        ]);
    
        // Simpan relasi ke tabel pivot dosen_matkul
        foreach ($matkul_ids as $id_matkul) {
            $db->table('dosen_matkul')->insert([
                'id_dosen' => $id_dosen,
                'id_matkul' => $id_matkul
            ]);
        }
    
        $db->transComplete();
    
        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data dosen dan matkul.');
        }
    
        return redirect()->to('/admin/dosen')->with('success', 'Data dosen dan matkul berhasil ditambahkan.');
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
        $session = session();

        // Kirim data session ke view
        $data = [
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];
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
        $session = session();

        // Kirim data session dan data lainnya ke view
        $data = [
            'nim'     => $session->get('nim'),
            'level'   => $session->get('level')
        ];

        $matkulModel    = new Matkul_model();
        $ruangModel     = new Ruang_model();
        $dosenModel     = new Dosen_model();
        $fakultasModel  = new Fakultas_model();
        $prodiModel     = new Prodi_model();

        $data['matkul']   = $matkulModel->findAll();
        $data['ruang']    = $ruangModel->findAll();
        $data['dosen']    = $dosenModel->findAll();
        $data['fakultas'] = $fakultasModel->findAll();
        $data['prodi']    = $prodiModel->findAll();

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
            $matkul = $matkulModel->find($id);
            if (!$matkul) {
                return redirect()->to('/admin/matkul')->with('error', 'Mata kuliah tidak ditemukan.');
            }

            $matkulModel->delete($id);
            return redirect()->to('/admin/matkul')->with('success', 'Mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/matkul')->with('error', 'Gagal menghapus mata kuliah.');
        }
    }





    //MASTER PRODI
    public function prodi()
    {
        $session = session();

        // Kirim data session ke view
        $data = [
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];
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
        $session = session();

        // Kirim data session ke view
        $data = [
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];

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
        $session = session();

        // Kirim data session ke view
        $data = [
            'nim'   => $session->get('nim'),
            'level' => $session->get('level')
        ];
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

        return redirect()->to('/admin/setupjadwal')->with('success', 'Jadwal berhasil disimpan.');

    }

    public function deletejadwal($id)
    {
        $jadwalModel = new Jadwal_model();
        $jadwalModel->delete($id);

        return redirect()->to('/admin/setupjadwal')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function tambahdosen()
    {
        $dosenModel = new Dosen_model();
        $matkulModel = new Matkul_model();

        $data['dosen'] = $dosenModel->findAll();
        $data['matkul'] = $matkulModel->findAll();

        return view('admin/dosen', $data);
    }

    public function simpanDosen()
{
    $dosenModel = new \App\Models\Dosen_model();
    $akunModel = new \App\Models\Akun_model();

    $dataDosen = [
        'nidn'          => $this->request->getPost('nidn'),
        'kode_dosen'    => $this->request->getPost('kode_dosen'),
        'nama_dosen'    => $this->request->getPost('nama_dosen'),
        'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        'matkul_diampu' => $this->request->getPost('matkul'),
        'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
    ];

    $dosenModel->insert($dataDosen);

    $dataAkun = [
        'nim'      => $this->request->getPost('nidn'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'level'    => 'dosen',
    ];

    $akunModel->insert($dataAkun);

    return redirect()->to('/admin/dosen')->with('success', 'Data dosen berhasil disimpan');
}



}

