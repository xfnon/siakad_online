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
$routes->get('/admin/dosen', 'Admin::dosen');
$routes->get('/admin/matkul', 'Admin::matkul');
$routes->get('/admin/akun', 'Admin::akun');
$routes->get('/admin/tahun', 'Admin::tahun');
$routes->get('/admin/ruang', 'Admin::ruang');
$routes->get('/admin/jurusan', 'Admin::jurusan');
$routes->get('/admin/semester', 'Admin::semester');
//adminprocess
$routes->post('admin/savemhs', 'Admin::savemhs');
$routes->post('admin/savedosen', 'Admin::savedosen');
$routes->post('admin/savejurusan', 'Admin::savejurusan');
$routes->post('/admin/saveRuang', 'Admin::saveRuang');
$routes->post('/admin/savematkul', 'Admin::saveMatkul');
$routes->post('admin/savetahun', 'Admin::savetahun');
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


$routes->get('/admin/fakultas', 'Admin::fakultas');
$routes->post('/admin/saveFakultas', 'Admin::saveFakultas');
$routes->post('/admin/editFakultas', 'Admin::editFakultas');
$routes->get('/admin/deleteFakultas/(:num)', 'Admin::deleteFakultas/$1');
//hapus fakul
$routes->get('/admin/deleteFakultas/(:num)', 'Admin::deleteFakultas/$1');

$routes->get('/admin/prodi', 'Admin::prodi'); // Menampilkan daftar prodi
//$routes->post('/admin/prodi/save', 'Admin::saveProdi'); // Menyimpan prodi baru
$routes->post('/admin/prodi/edit', 'Admin::editProdi'); // Mengedit prodi
$routes->get('/admin/prodi/delete/(:num)', 'Admin::deleteProdi/$1');
$routes->post('admin/saveProdi', 'Admin::saveProdi');

 // Menghapus prodi




