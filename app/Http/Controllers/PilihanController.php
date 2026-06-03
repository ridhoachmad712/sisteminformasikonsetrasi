<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class PilihanController extends Controller
{
    private array $konsentrasi = [
        'pemasaran' => 'Manajemen Pemasaran',
        'keuangan'  => 'Manajemen Keuangan',
        'sdm'       => 'Manajemen SDM',
    ];

    public function index()
    {
        $mahasiswa   = Mahasiswa::findOrFail(session('mahasiswa_id'));
        $konsentrasi = $this->konsentrasi;
        $pilihan     = $mahasiswa->pilihan_konsentrasi ?? [];

        return view('pilihan.index', compact('mahasiswa', 'konsentrasi', 'pilihan'));
    }

    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail(session('mahasiswa_id'));
        $valid     = array_keys($this->konsentrasi);

        $request->validate([
            'pilihan'   => 'required|array|size:3',
            'pilihan.*' => 'required|in:' . implode(',', $valid),
        ], [
            'pilihan.required' => 'Lengkapi ketiga pilihan konsentrasi.',
            'pilihan.*.in'     => 'Konsentrasi tidak valid.',
        ]);

        $pilihan = array_values($request->input('pilihan'));

        // Harus 3 konsentrasi berbeda (permutasi unik)
        if (count(array_unique($pilihan)) !== 3) {
            return back()->withErrors([
                'pilihan' => 'Pilihan 1, 2, dan 3 harus berbeda.',
            ])->withInput();
        }

        $mahasiswa->update([
            'pilihan_konsentrasi'     => $pilihan,
            'sudah_pilih_konsentrasi' => true,
        ]);

        return redirect()->route('beranda')->with('success', 'Pilihan konsentrasi berhasil disimpan.');
    }
}
