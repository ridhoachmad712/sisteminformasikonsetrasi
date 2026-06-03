<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\HasilTes::with('mahasiswa');
        if ($request->rekomendasi) $query->where('rekomendasi', $request->rekomendasi);
        if ($request->angkatan) $query->whereHas('mahasiswa', fn($q) => $q->where('angkatan', $request->angkatan));
        if ($request->search) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('nama', 'like', "%{$request->search}%")
                                                        ->orWhere('nim', 'like', "%{$request->search}%"));
        }
        $hasil = $query->latest()->paginate(20);
        $angkatanList = \App\Models\Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        return view('admin.hasil.index', compact('hasil', 'angkatanList'));
    }

    public function show(\App\Models\HasilTes $hasil)
    {
        $hasil->load(['mahasiswa', 'detailJawaban.soal']);
        return view('admin.hasil.show', compact('hasil'));
    }

    public function export(Request $request)
    {
        $query = \App\Models\HasilTes::with('mahasiswa');
        if ($request->rekomendasi) $query->where('rekomendasi', $request->rekomendasi);
        if ($request->angkatan) $query->whereHas('mahasiswa', fn($q) => $q->where('angkatan', $request->angkatan));
        $hasil = $query->latest()->get();

        $csv = "NIM,Nama,Angkatan,Rekomendasi,Nilai Pemasaran,Nilai Keuangan,Nilai SDM,Tanggal Tes\n";
        foreach ($hasil as $h) {
            $csv .= "\"{$h->mahasiswa->nim}\",\"{$h->mahasiswa->nama}\",\"{$h->mahasiswa->angkatan}\",\"{$h->label_rekomendasi}\",{$h->nilai_pemasaran},{$h->nilai_keuangan},{$h->nilai_sdm},\"{$h->created_at->format('d/m/Y H:i')}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="hasil-tes-konsentrasi.csv"',
        ]);
    }
}
