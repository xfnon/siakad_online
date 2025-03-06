<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//routes login
$routes->get('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('auth/create', 'Auth::create');
$routes->post('auth/authentication', 'Auth::authentication');

//routes admin
$routes->get('/admin/dashboard', 'Admin::dashboard');
$routes->get('/admin/mahasiswa', 'Admin::mahasiswa');
$routes->get('/admin/dosen', 'Admin::dosen');
$routes->get('/admin/matkul', 'Admin::matkul');
$routes->get('/admin/akun', 'Admin::akun');
$routes->get('/admin/tahun', 'Admin::tahun');
$routes->get('/admin/ruang', 'Admin::ruang');
$routes->get('/admin/jurusan', 'Admin::jurusan');
$routes->get('/admin/semester', 'Admin::semester');
$routes->get('/admin/setupjadwal', 'Admin::setupjadwal');
//adminprocess
$routes->post('admin/savemhs', 'Admin::savemhs');
$routes->post('admin/savedosen', 'Admin::savedosen');
$routes->post('admin/savejurusan', 'Admin::savejurusan');
$routes->post('/admin/saveRuang', 'Admin::saveRuang');
$routes->post('/admin/savematkul', 'Admin::saveMatkul');
$routes->post('admin/savetahun', 'Admin::savetahun');
$routes->post('admin/savejadwal', 'Admin::savejadwal');
//update
$routes->post('/admin/updatedosen', 'Admin::updatedosen');
$routes->post('/admin/updatematkul', 'Admin::updateMatkul');
$routes->post('/admin/updateRuang', 'Admin::updateRuang');
$routes->post('/admin/editMatkul', 'Admin::editMatkul');

//hapus
$routes->get('/admin/deletemhs/(:segment)', 'Admin::deletemhs/$1');
$routes->get('/admin/deletedosen/(:segment)', 'Admin::deletedosen/$1');
$routes->get('/admin/deleteMatkul/(:num)', 'Admin::deleteMatkul/$1');
$routes->get('/admin/deletejurusan/(:segment)', 'Admin::deletejurusan/$1');
$routes->get('/admin/deleteRuang/(:segment)', 'Admin::deleteRuang/$1');


// routes mahasiswa
$routes->get('/mahasiswa/dashboard', 'Mahasiswa::dashboard');
$routes->get('/mahasiswa/rencanastudi', 'Mahasiswa::rencanastudi');
$routes->get('/mahasiswa/kelas', 'Mahasiswa::kelas');
$routes->get('/mahasiswa/jadwal', 'Mahasiswa::jadwal');
$routes->get('/mahasiswa/hasilstudi', 'Mahasiswa::hasilstudi');
$routes->get('/mahasiswa/transkripnilai', 'Mahasiswa::transkripnilai');
$routes->get('/mahasiswa/panduankrs', 'Mahasiswa::panduankrs');
$routes->get('/mahasiswa/krs/(:segment)', 'Mahasiswa::krs/$1');
$routes->get('/mahasiswa/krs/(:segment)/(:segment)', 'Mahasiswa::krs/$1/$2');
$routes->get('/mahasiswa/krs/(:segment)/(:segment)/(:segment)', 'Mahasiswa::krs/$1/$2/$3');


//routes master-mhs
$routes->get('/master-mhs/index', 'Mahasiswa::index');
$routes->get('/master-mhs/input', 'Mahasiswa::input');
$routes->get('/master-mhs/datamhs', 'Mahasiswa::datamhs');
$routes->post('master-mhs/save', 'Mahasiswa::save');
$routes->get('/master-mhs/delete/(:segment)', 'Mahasiswa::delete/$1');
$routes->get('/master-mhs/edit/(:segment)', 'Mahasiswa::edit/$1');
$routes->post('master-mhs/update', 'Mahasiswa::update');


$routes->get('/blank', 'Mahasiswa::blank');
