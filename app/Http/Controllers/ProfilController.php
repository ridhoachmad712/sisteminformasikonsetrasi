<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $mahasiswa = \App\Models\Mahasiswa::findOrFail(session('mahasiswa_id'));
        $hasil     = $mahasiswa->hasilTesTerakhir;
        return view('profil.index', compact('mahasiswa', 'hasil'));
    }

}
