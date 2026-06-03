<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class NilaiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::findOrFail(session('mahasiswa_id'));
        $grup      = config('matakuliah.mata_kuliah');
        $pilihan   = config('matakuliah.pilihan');
        $nilai     = $mahasiswa->nilai_matkul ?? [];

        return view('nilai.index', compact('mahasiswa', 'grup', 'pilihan', 'nilai'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail(session('mahasiswa_id'));
        $grup      = config('matakuliah.mata_kuliah');
        $pilihan   = config('matakuliah.pilihan');

        // Kumpulkan semua key mata kuliah
        $semuaKey = [];
        foreach ($grup as $g) {
            $semuaKey = array_merge($semuaKey, array_keys($g['items']));
        }

        // Aturan validasi: setiap mata kuliah wajib & harus salah satu nilai valid
        $rules = [];
        foreach ($semuaKey as $key) {
            $rules["nilai.$key"] = ['required', 'in:' . implode(',', $pilihan)];
        }
        $request->validate($rules, [
            'nilai.*.required' => 'Semua nilai mata kuliah wajib diisi.',
            'nilai.*.in'       => 'Nilai tidak valid.',
        ]);

        // Simpan hanya key yang dikenal
        $bersih = [];
        foreach ($semuaKey as $key) {
            $bersih[$key] = $request->input("nilai.$key");
        }

        $mahasiswa->update([
            'nilai_matkul'      => $bersih,
            'sudah_input_nilai' => true,
        ]);

        return redirect()->route('beranda')->with('success', 'Nilai mata kuliah berhasil disimpan.');
    }
}
