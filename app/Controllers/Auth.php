<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

class Auth extends BaseController
{
    public function login()
    {
        echo view('auth/login');
    }

    public function register()
    {
        echo view('auth/testregist');
    }

    public function create()
    {
        $session = session();
        $model = new User_model();

        $nim = $this->request->getPost('nim');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $level = $this->request->getPost('level');

        $data = [
            'nim'      => $nim,
            'password' => $password,
            'level'    => $level
        ];

        $model->insert($data);

        $session->setFlashdata('msg', 'Akun berhasil dibuat!');
        return redirect()->to('/login');
    }

    public function auth()
    {
        $session = session();
        $model = new User_model();
        $nim = $this->request->getVar('nim');
        $password = $this->request->getVar('password');

        $data = $model->where('nim', $nim)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                $ses_data = [
                    'id_akun'   => $data['id_akun'],
                    'nim'       => $data['nim'], // Menyimpan nim dalam session
                    'level'     => $data['level'],
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);

                // Redirect berdasarkan level
                switch ($data['level']) {
                    case 'admin':
                        return redirect()->to('/admin/dashboard');
                    case 'dosen':
                        return redirect()->to('/dosen/dashboard');
                    case 'mahasiswa':
                        return redirect()->to('/mahasiswa/dashboard');
                    default:
                        return redirect()->to('/dashboard');
                }
            } else {
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'NIM tidak ditemukan');
            return redirect()->to('/login');
        }
    }



    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
