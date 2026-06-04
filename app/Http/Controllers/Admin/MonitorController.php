<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Soal;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {
        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan','desc')->pluck('angkatan');
        // Hitung jumlah soal per jenis untuk denominator progress
        $jumlahSoal = [
            'minat' => Soal::where('aktif', true)->where('jenis', 'minat')->count(),
            'bakat' => Soal::where('aktif', true)->where('jenis', 'bakat')->count(),
        ];
        return view('admin.monitor.index', compact('angkatanList', 'jumlahSoal'));
    }

    /**
     * Endpoint AJAX yang return JSON ringan untuk polling.
     */
    public function data(Request $request)
    {
        $angkatan = $request->input('angkatan');
        $jenis    = $request->input('jenis');
        $batasIdle    = now()->subMinutes(5);          // > 5 menit tidak aktif = idle
        $batasSelesai = now()->subHour();              // submit ≤ 1 jam terakhir

        // Yang sedang tes: ada last_activity_at, tes_aktif, dan belum submit untuk jenis itu
        $sedangTes = Mahasiswa::query()
            ->whereNotNull('tes_aktif')
            ->where('last_activity_at', '>=', now()->subHours(2)) // buang yg sudah lama nganggur
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->when($jenis, fn($q) => $q->where('tes_aktif', $jenis))
            ->select('id','nim','nama','angkatan','tes_aktif','draft_minat','draft_bakat','last_activity_at')
            ->get();

        // Baru selesai 1 jam terakhir (untuk badge "Selesai")
        $baruSelesai = Mahasiswa::query()
            ->whereNull('tes_aktif')
            ->where('last_activity_at', '>=', $batasSelesai)
            ->where(fn($q) => $q->where('sudah_tes_minat', true)->orWhere('sudah_tes_bakat', true))
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->select('id','nim','nama','angkatan','sudah_tes_minat','sudah_tes_bakat','last_activity_at')
            ->get();

        $totalSoalMinat = Soal::where('aktif',true)->where('jenis','minat')->count() ?: 1;
        $totalSoalBakat = Soal::where('aktif',true)->where('jenis','bakat')->count() ?: 1;

        $rows = [];
        foreach ($sedangTes as $m) {
            $jenisAktif = $m->tes_aktif;
            $draft      = $jenisAktif === 'minat' ? ($m->draft_minat ?? []) : ($m->draft_bakat ?? []);
            $terjawab   = count($draft);
            $total      = $jenisAktif === 'minat' ? $totalSoalMinat : $totalSoalBakat;
            $idle       = $m->last_activity_at && $m->last_activity_at->lt($batasIdle);

            $rows[] = [
                'id'         => $m->id,
                'nim'        => $m->nim,
                'nama'       => $m->nama,
                'angkatan'   => $m->angkatan,
                'jenis'      => $jenisAktif,
                'terjawab'   => $terjawab,
                'total'      => $total,
                'persen'     => $total ? round($terjawab / $total * 100) : 0,
                'status'     => $idle ? 'idle' : 'aktif',
                'last_text'  => $m->last_activity_at?->diffForHumans(),
                'last_iso'   => $m->last_activity_at?->toIso8601String(),
            ];
        }

        foreach ($baruSelesai as $m) {
            // Tentukan jenis terakhir submit (yang punya last_activity terdekat)
            $rows[] = [
                'id'         => $m->id,
                'nim'        => $m->nim,
                'nama'       => $m->nama,
                'angkatan'   => $m->angkatan,
                'jenis'      => $m->sudah_tes_bakat && !$m->sudah_tes_minat ? 'bakat' : ($m->sudah_tes_minat && !$m->sudah_tes_bakat ? 'minat' : 'selesai'),
                'terjawab'   => null,
                'total'      => null,
                'persen'     => 100,
                'status'     => 'selesai',
                'last_text'  => $m->last_activity_at?->diffForHumans(),
                'last_iso'   => $m->last_activity_at?->toIso8601String(),
            ];
        }

        // Stat ringkas
        $stat = [
            'aktif'      => $sedangTes->count(),
            'selesai_1j' => $baruSelesai->count(),
            'idle'       => collect($rows)->where('status','idle')->count(),
            'updated_at' => now()->toIso8601String(),
        ];

        return response()->json(['rows' => $rows, 'stat' => $stat]);
    }
}
