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

        $csv = "NIM,Nama,Angkatan,IPK,Pilihan 1,Pilihan 2,Pilihan 3,Rekomendasi,Nilai Pemasaran,Nilai Keuangan,Nilai SDM,Tanggal Tes\n";
        foreach ($hasil as $h) {
            $m       = $h->mahasiswa;
            $ipk     = $m->ipk !== null ? number_format($m->ipk, 2) : '';
            $pilihan = $m->pilihan_konsentrasi ?? [];
            $p1      = \App\Models\Mahasiswa::labelKonsentrasi($pilihan[0] ?? '');
            $p2      = \App\Models\Mahasiswa::labelKonsentrasi($pilihan[1] ?? '');
            $p3      = \App\Models\Mahasiswa::labelKonsentrasi($pilihan[2] ?? '');
            $csv .= "\"{$m->nim}\",\"{$m->nama}\",\"{$m->angkatan}\",\"{$ipk}\",\"{$p1}\",\"{$p2}\",\"{$p3}\",\"{$h->label_rekomendasi}\",{$h->nilai_pemasaran},{$h->nilai_keuangan},{$h->nilai_sdm},\"{$h->created_at->format('d/m/Y H:i')}\"\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="hasil-tes-konsentrasi.csv"',
        ]);
    }
}
