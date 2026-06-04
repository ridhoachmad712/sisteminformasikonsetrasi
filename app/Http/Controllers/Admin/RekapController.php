<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with('hasilTesTerakhir');

        if ($request->search) {
            $query->where(fn($q) => $q
                ->where('nim', 'like', "%{$request->search}%")
                ->orWhere('nama', 'like', "%{$request->search}%"));
        }
        if ($request->angkatan) {
            $query->where('angkatan', $request->angkatan);
        }
        if ($request->status === 'lengkap') {
            $query->where('sudah_tes', true)
                  ->where('sudah_input_nilai', true)
                  ->where('sudah_pilih_konsentrasi', true);
        } elseif ($request->status === 'belum') {
            $query->where(fn($q) => $q
                ->where('sudah_tes', false)
                ->orWhere('sudah_input_nilai', false)
                ->orWhere('sudah_pilih_konsentrasi', false));
        }

        $mahasiswa    = $query->orderBy('angkatan','desc')->orderBy('nama')->paginate(20);
        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan','desc')->pluck('angkatan');

        return view('admin.rekap.index', compact('mahasiswa', 'angkatanList'));
    }

    public function show(Mahasiswa $mahasiswum)
    {
        $mahasiswa = $mahasiswum->load('hasilTesTerakhir');
        $mkData    = $mahasiswa->nilaiMkPerKonsentrasi();
        return view('admin.rekap.show', compact('mahasiswa', 'mkData'));
    }
}
