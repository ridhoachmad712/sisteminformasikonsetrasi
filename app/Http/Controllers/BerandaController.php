<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $mahasiswa   = \App\Models\Mahasiswa::findOrFail(session('mahasiswa_id'));
        $jadwalMinat = \App\Models\JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, 'minat');
        $jadwalBakat = \App\Models\JadwalTes::getUntukAngkatanDanJenis($mahasiswa->angkatan, 'bakat');
        $hasil       = $mahasiswa->hasilTesTerakhir;

        return view('beranda', compact('mahasiswa', 'jadwalMinat', 'jadwalBakat', 'hasil'));
    }
}
