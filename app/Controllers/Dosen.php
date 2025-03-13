<?php

namespace App\Controllers;

class Dosen extends BaseController
{
    public function dashboard()
    {
        return view('dosen/dashboard'); // Menampilkan view dashboard
    }

    public function persetujuan()
    {
        return view('dosen/persetujuan'); // Menampilkan view dashboard
    }

    public function konfirmasi()
    {
        return view('dosen/konfirmasi'); // Menampilkan view dashboard
    }

    public function absensi()
    {
        return view('dosen/absensi'); // Menampilkan view dashboard
    }

    public function penilaian()
    {
        return view('dosen/penilaian'); // Menampilkan view dashboard
    }
}
