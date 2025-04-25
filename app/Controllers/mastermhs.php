<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Mahasiswa_model;
use App\Models\Fakultas_model;
use App\Models\Prodi_model;

class Mahasiswa extends BaseController
{

	//master-mhs
	public function dashb()
	{
		echo view('master-mhs/index');
	}

	public function input()
	{
		echo view('master-mhs/input');
	}

	public function datamhs()
{
    $mahasiswaModel = new Mahasiswa_model();
    $fakultasModel = new Fakultas_model();
    $prodiModel    = new Prodi_model();

    $data['mahasiswa'] = $mahasiswaModel->getMahasiswa();
    $data['fakultas']  = $fakultasModel->findAll();
    $data['prodi']     = $prodiModel->findAll();

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
		return redirect()->to('master-mhs/datamhs');
	}

	public function edit($id)
    {
        $model = new Mahasiswa_model();
        $data['mahasiswa'] = $model->getMahasiswa($id)->getRow();
        echo view('master-mhs/edit', $data);
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
        return redirect()->to('master-mhs/datamhs');
    }


	public function blank()
	{
		echo view('blank');
	}


}