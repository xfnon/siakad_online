<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//routes login
$routes->get('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/logout', 'Auth::logout');
$routes->post('auth/create', 'Auth::create');
$routes->post('auth/auth', 'Auth::auth');



//routes admin
$routes->get('/admin/dashboard', 'Admin::dashboard', ['filter' => 'auth:admin']);
$routes->get('/admin/dosen', 'Admin::dosen', ['filter' => 'auth:admin']);
$routes->get('/admin/matkul', 'Admin::matkul', ['filter' => 'auth:admin']);
$routes->get('/admin/akun', 'Admin::akun', ['filter' => 'auth:admin']);
$routes->get('/admin/tahun', 'Admin::tahun', ['filter' => 'auth:admin']);
$routes->get('/admin/ruang', 'Admin::ruang', ['filter' => 'auth:admin']);
$routes->get('/admin/jurusan', 'Admin::jurusan', ['filter' => 'auth:admin']);
$routes->get('/admin/semester', 'Admin::semester', ['filter' => 'auth:admin']);
$routes->get('/admin/setupjadwal', 'Admin::setupjadwal', ['filter' => 'auth:admin']);
$routes->get('/admin/fakultas', 'Admin::fakultas', ['filter' => 'auth:admin']);
$routes->get('/admin/mahasiswa', 'Admin::mahasiswa', ['filter' => 'auth:admin']);
$routes->get('/admin/prodi', 'Admin::prodi', ['filter' => 'auth:admin']);

//adminprocess
$routes->post('admin/savemhs', 'Admin::saveMahasiswa');
$routes->post('admin/savedosen', 'Admin::savedosen');
$routes->post('admin/savejurusan', 'Admin::savejurusan');
$routes->post('/admin/saveRuang', 'Admin::saveRuang');
$routes->post('/admin/savematkul', 'Admin::saveMatkul');
$routes->post('admin/savetahun', 'Admin::savetahun');
$routes->post('admin/savejadwal', 'Admin::savejadwal');
$routes->post('/admin/saveFakultas', 'Admin::saveFakultas');
$routes->post('admin/saveProdi', 'Admin::saveProdi');
$routes->post('/admin/prodi/edit', 'Admin::editProdi'); // Mengedit prodi

//updateprocess
$routes->post('/admin/updatedosen', 'Admin::updatedosen');
$routes->post('/admin/updatematkul', 'Admin::updateMatkul');
$routes->post('/admin/updateRuang', 'Admin::updateRuang');
$routes->post('/admin/updateMahasiswa', 'Admin::updateMahasiswa');
$routes->post('/admin/editMatkul', 'Admin::editMatkul');
$routes->post('/admin/editFakultas', 'Admin::editFakultas');

//admindel
$routes->get('/admin/deletemahasiswa/(:segment)', 'Admin::deletemahasiswa/$1');
$routes->get('/admin/deletedosen/(:segment)', 'Admin::deletedosen/$1');
$routes->get('/admin/deleteMatkul/(:num)', 'Admin::deleteMatkul/$1');
$routes->get('/admin/deletejurusan/(:segment)', 'Admin::deletejurusan/$1');
$routes->get('/admin/deleteRuang/(:segment)', 'Admin::deleteRuang/$1');
$routes->get('/admin/deleteFakultas/(:num)', 'Admin::deleteFakultas/$1');
$routes->get('/admin/deleteFakultas/(:num)', 'Admin::deleteFakultas/$1');
$routes->get('/admin/prodi/delete/(:num)', 'Admin::deleteProdi/$1');
$routes->get('/admin/deletejadwal/(:num)', 'Admin::deletejadwal/$1');



//routes dosen

$routes->get('/dosen/dashboard', 'Dosen::dashboard', ['filter' => 'auth:dosen']);
$routes->get('/dosen/persetujuan', 'Dosen::persetujuan', ['filter' => 'auth:dosen']);
$routes->get('/dosen/konfirmasi', 'Dosen::konfirmasi', ['filter' => 'auth:dosen']);
$routes->get('/dosen/absensi', 'Dosen::absensi', ['filter' => 'auth:dosen']);
$routes->get('/dosen/penilaian', 'Dosen::penilaian', ['filter' => 'auth:dosen']);


//routes mahasiswa
$routes->get('/mahasiswa/dashboard', 'Mahasiswa::dashboard');
