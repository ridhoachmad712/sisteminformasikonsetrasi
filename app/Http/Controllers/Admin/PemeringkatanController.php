<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PemeringkatanController extends Controller
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

        // Ambil semua data dulu (perlu hitung skor di PHP karena tidak ada di DB)
        $rows = $query->orderBy('angkatan', 'desc')->orderBy('nama')->get()
            ->map(function ($m) {
                $skor = $m->hitungSkorFinal();
                if (!$skor) return null;

                $total = $skor['total']; // sudah arsort, urutan 1-2-3
                $keys  = array_keys($total);

                return [
                    'mahasiswa' => $m,
                    'rank1'     => ['k' => $keys[0], 'v' => $total[$keys[0]]],
                    'rank2'     => ['k' => $keys[1], 'v' => $total[$keys[1]]],
                    'rank3'     => ['k' => $keys[2], 'v' => $total[$keys[2]]],
                    'rekomendasi' => $skor['rekomendasi'],
                    'lengkap'   => $skor['lengkap'],
                ];
            })
            ->filter();

        // Filter berdasarkan konsentrasi rekomendasi
        if ($request->konsentrasi) {
            $rows = $rows->where('rekomendasi', $request->konsentrasi);
        }

        // Sort by skor tertinggi (peringkat 1 dari skor terbesar)
        if ($request->sort === 'skor') {
            $rows = $rows->sortByDesc(fn($r) => $r['rank1']['v']);
        }

        // Manual pagination
        $perPage = 25;
        $page    = $request->input('page', 1);
        $paged   = $rows->forPage($page, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged,
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $angkatanList = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        $stat = [
            'total'     => $rows->count(),
            'pemasaran' => $rows->where('rekomendasi', 'pemasaran')->count(),
            'keuangan'  => $rows->where('rekomendasi', 'keuangan')->count(),
            'sdm'       => $rows->where('rekomendasi', 'sdm')->count(),
        ];

        return view('admin.peringkat.index', [
            'rows'         => $paginator,
            'angkatanList' => $angkatanList,
            'stat'         => $stat,
        ]);
    }
}
