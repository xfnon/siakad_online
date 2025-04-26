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
$routes->post('admin/admin/savejadwal', 'Admin::saveJadwal');
$routes->get('admin/jadwal', 'Admin::jadwal');


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



// Routes untuk dosen
$routes->get('/dosen/dashboard', 'Dosen::dashboard', ['filter' => 'auth:dosen']);
$routes->get('/dosen/persetujuan', 'Dosen::persetujuan', ['filter' => 'auth:dosen']);
$routes->get('/dosen/konfirmasi', 'Dosen::konfirmasi', ['filter' => 'auth:dosen']); // Tanpa parameter (opsional)
$routes->get('/dosen/absensi', 'Dosen::absensi', ['filter' => 'auth:dosen']);
$routes->get('/dosen/penilaian', 'Dosen::penilaian', ['filter' => 'auth:dosen']);


// Route dengan parameter NIM
$routes->get('/dosen/konfirmasi/(:segment)', 'Dosen::konfirmasi/$1', ['filter' => 'auth:dosen']);
$routes->get('/dosen/setujuiKrs/(:segment)', 'Dosen::setujuiKrs/$1', ['filter' => 'auth:dosen']);
$routes->get('/dosen/tolakKrs/(:segment)', 'Dosen::tolakKrs/$1', ['filter' => 'auth:dosen']);







//routes mahasiswa
$routes->get('/mahasiswa/dashboard', 'Mahasiswa::dashboard', ['filter' => 'auth:mahasiswa']);
$routes->get('/mahasiswa/rencanastudi', 'Mahasiswa::rencanastudi', ['filter' => 'auth:mahasiswa']);
$routes->post('mahasiswa/konfirmasiKrs', 'Mahasiswa::konfirmasiKrs', ['filter' => 'auth:mahasiswa']);
// Route untuk dosen melihat detail pengajuan KRS
$routes->get('mahasiswa/jadwal', 'Mahasiswa::jadwal', ['filter' => 'auth:mahasiswa']);
$routes->get('/mahasiswa/transkripnilai', 'Mahasiswa::transkripnilai', ['filter' => 'auth:mahasiswa']);
$routes->get('/mahasiswa/hasilstudi', 'Mahasiswa::hasilstudi', ['filter' => 'auth:mahasiswa']);



$routes->get('/admin/dosen', 'Admin::dosen');
$routes->post('/admin/saveDosen', 'Admin::simpanDosen');


$routes->get('dosen/penilaian/(:num)', 'Dosen::penilaian/$1');

$routes->post('dosen/simpanNilai', 'Dosen::simpanNilai');
$routes->get('dosen/nilai/(:num)', 'DosenController::nilaiMahasiswa/$1');
