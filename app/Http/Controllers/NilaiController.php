<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\DosenPa;

class NilaiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::findOrFail(session('mahasiswa_id'));
        $pilihan   = config('matakuliah.pilihan');
        $nilai     = $mahasiswa->nilai_matkul ?? [];

        // Daftar 9 mata kuliah dalam satu list, urutan diselang-seling
        // agar pengelompokan konsentrasi tidak terlihat oleh mahasiswa.
        $grup    = config('matakuliah.mata_kuliah');
        $kolom   = array_values(array_map(fn($g) => $g['items'], $grup)); // 3 grup × 3 MK
        $mataKuliah = [];
        for ($i = 0; $i < 3; $i++) {
            foreach ($kolom as $items) {
                $key  = array_keys($items)[$i];
                $mataKuliah[$key] = $items[$key];
            }
        }

        $dosenList = DosenPa::where('aktif', true)->orderBy('nama')->get();

        return view('nilai.index', compact('mahasiswa', 'mataKuliah', 'pilihan', 'nilai', 'dosenList'));
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

        // Aturan validasi: IPK + setiap mata kuliah wajib & harus salah satu nilai valid
        $rules = [
            'ipk'         => ['required', 'numeric', 'between:0,4'],
            'dosen_pa_id' => ['required', 'exists:dosen_pa,id'],
            'pernyataan'  => ['accepted'],
        ];
        foreach ($semuaKey as $key) {
            $rules["nilai.$key"] = ['required', 'in:' . implode(',', $pilihan)];
        }
        $request->validate($rules, [
            'ipk.required'     => 'IPK wajib diisi.',
            'ipk.numeric'      => 'IPK harus berupa angka.',
            'ipk.between'      => 'IPK harus antara 0,00 sampai 4,00.',
            'nilai.*.required' => 'Semua nilai mata kuliah wajib diisi.',
            'nilai.*.in'       => 'Nilai tidak valid.',
            'dosen_pa_id.required' => 'Dosen PA wajib dipilih.',
            'dosen_pa_id.exists'   => 'Dosen PA tidak valid.',
            'pernyataan.accepted'  => 'Anda harus menyetujui pernyataan integritas data.',
        ]);

        // Simpan hanya key yang dikenal
        $bersih = [];
        foreach ($semuaKey as $key) {
            $bersih[$key] = $request->input("nilai.$key");
        }

        $mahasiswa->update([
            'nilai_matkul'      => $bersih,
            'ipk'               => $request->input('ipk'),
            'dosen_pa_id'       => $request->input('dosen_pa_id') ?: null,
            'sudah_input_nilai' => true,
        ]);

        return redirect()->route('beranda')->with('success', 'Data nilai akademik berhasil disimpan.');
    }
}
