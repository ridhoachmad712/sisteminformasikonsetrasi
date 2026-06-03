<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = \App\Models\Mahasiswa::count();
        $sudahTes       = \App\Models\Mahasiswa::where('sudah_tes', true)->count();
        $sudahMinat     = \App\Models\Mahasiswa::where('sudah_tes_minat', true)->count();
        $sudahBakat     = \App\Models\Mahasiswa::where('sudah_tes_bakat', true)->count();
        $belumTes       = $totalMahasiswa - $sudahTes;

        $distribusi = \App\Models\HasilTes::selectRaw('rekomendasi, count(*) as total')
            ->groupBy('rekomendasi')
            ->pluck('total', 'rekomendasi');

        $hasilTerbaru = \App\Models\HasilTes::with('mahasiswa')
            ->latest()
            ->take(10)
            ->get();

        // Jadwal yang akan datang atau sedang berlangsung
        $jadwalAktif = \App\Models\JadwalTes::where('aktif', true)
            ->where('tanggal_selesai', '>=', now())
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa', 'sudahTes', 'sudahMinat', 'sudahBakat',
            'belumTes', 'distribusi', 'hasilTerbaru', 'jadwalAktif'
        ));
    }
}
