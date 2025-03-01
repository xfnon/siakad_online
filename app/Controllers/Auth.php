<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\User_model;

class Auth extends BaseController
{

	//master-mhs
	public function login()
	{
		echo view('auth/login');
	}

	public function authentication()
{
    $validate = $this->validate([
        'nim' => [  // Changed 'username' to 'nim'
            'rules' => 'required',
            'errors' => [
                'required' => 'NIM harus diisi.'  // Updated error message
            ]
        ],
        'password' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Password harus diisi.'
            ]
        ]
    ]);

    if (!$validate) {
        $validation = \Config\Services::validation();
        return redirect()->to('/')->withInput()->with('validation', $validation);
    }

    $userModel = new User_model();
    $cek_user = $userModel->getUser ($this->request->getVar('nim'));  // Changed 'username' to 'nim'

    // jika nim tidak ditemukan
    if (!$cek_user) {
        return redirect()->to('/')->with('msg', '<div class="alert alert-danger">NIM tidak ditemukan.</div>');  // Updated error message
    }

    // jika password salah
    if (!password_verify($this->request->getVar('password'), $cek_user['password'])) {
        return redirect()->to('/')->with('msg', '<div class="alert alert-danger">Password salah.</div>');
    }

    // LOGIN BERHASIL
    // set session
    $newdata = [
        'id' => $cek_user['id'],
        'level' => $cek_user['level'],
        'logged_in' => TRUE
    ];
    $this->session->set($newdata);

    // Redirect based on user level
    if ($cek_user['level'] === 'superadmin') {
        return redirect()->to('/superadmin/dashboard');
    } else {
        return redirect()->to('/dashboard');
    }
}

	public function register()
	{
		echo view('auth/testregist');
	}

	public function create()
    {
        $model = new User_model();
        $data = array(
            'nim' => $this->request->getVar('nim'),
            'password' => password_hash($this->request->getVar('username'), PASSWORD_DEFAULT),
			'level' => $this->request->getVar('level'),
        );
        $model->saveUser($data);
        return redirect()->to('master-mhs/datamhs');
    }


}