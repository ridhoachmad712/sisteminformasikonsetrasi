<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PemeringkatanController extends Controller
{
    /** Ambil & olah data peringkat sesuai filter — dipakai index() dan export() */
    private function getRanking(Request $request)
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

        $rows = $query->orderBy('angkatan', 'desc')->orderBy('nama')->get()
            ->map(function ($m) {
                $skor = $m->hitungSkorFinal();
                if (!$skor) return null;
                $total = $skor['total'];
                $keys  = array_keys($total);
                return [
                    'mahasiswa'   => $m,
                    'rank1'       => ['k' => $keys[0], 'v' => $total[$keys[0]]],
                    'rank2'       => ['k' => $keys[1], 'v' => $total[$keys[1]]],
                    'rank3'       => ['k' => $keys[2], 'v' => $total[$keys[2]]],
                    'rekomendasi' => $skor['rekomendasi'],
                    'breakdown'   => $skor['breakdown'],
                    'lengkap'     => $skor['lengkap'],
                ];
            })
            ->filter();

        if ($request->konsentrasi) {
            $rows = $rows->where('rekomendasi', $request->konsentrasi);
        }
        if ($request->sort === 'skor') {
            $rows = $rows->sortByDesc(fn($r) => $r['rank1']['v']);
        }

        return $rows;
    }

    public function index(Request $request)
    {
        $rows = $this->getRanking($request);

        $perPage = 25;
        $page    = $request->input('page', 1);
        $paged   = $rows->forPage($page, $perPage);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged, $rows->count(), $perPage, $page,
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

    public function export(Request $request)
    {
        $rows = $this->getRanking($request);
        $label = ['pemasaran' => 'Pemasaran', 'keuangan' => 'Keuangan', 'sdm' => 'SDM'];

        $filename = 'pemeringkatan-konsentrasi-' . now()->format('Y-m-d-His') . '.csv';

        $callback = function () use ($rows, $label) {
            $out = fopen('php://output', 'w');
            // BOM untuk Excel agar UTF-8 aman
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($out, [
                'No', 'NIM', 'Nama', 'Angkatan',
                'Hasil Konsentrasi',
                'Peringkat 1', 'Skor 1',
                'Peringkat 2', 'Skor 2',
                'Peringkat 3', 'Skor 3',
                'MINAT Pemasaran', 'MINAT Keuangan', 'MINAT SDM',
                'MK Pemasaran', 'MK Keuangan', 'MK SDM',
                'Tes Pemasaran', 'Tes Keuangan', 'Tes SDM',
                'Prestasi Pemasaran', 'Prestasi Keuangan', 'Prestasi SDM',
                'IPK',
            ]);

            $no = 0;
            foreach ($rows as $r) {
                $no++;
                $m = $r['mahasiswa'];
                $b = $r['breakdown'];
                fputcsv($out, [
                    $no,
                    $m->nim,
                    $m->nama,
                    $m->angkatan,
                    $label[$r['rekomendasi']],
                    $label[$r['rank1']['k']], $r['rank1']['v'],
                    $label[$r['rank2']['k']], $r['rank2']['v'],
                    $label[$r['rank3']['k']], $r['rank3']['v'],
                    $b['minat']['pemasaran']['kontribusi'] ?? 0,
                    $b['minat']['keuangan']['kontribusi']  ?? 0,
                    $b['minat']['sdm']['kontribusi']       ?? 0,
                    $b['matkul']['pemasaran']['kontribusi'] ?? 0,
                    $b['matkul']['keuangan']['kontribusi']  ?? 0,
                    $b['matkul']['sdm']['kontribusi']       ?? 0,
                    $b['tes']['pemasaran']['kontribusi'] ?? 0,
                    $b['tes']['keuangan']['kontribusi']  ?? 0,
                    $b['tes']['sdm']['kontribusi']       ?? 0,
                    $b['prestasi']['pemasaran']['kontribusi'] ?? 0,
                    $b['prestasi']['keuangan']['kontribusi']  ?? 0,
                    $b['prestasi']['sdm']['kontribusi']       ?? 0,
                    $m->ipk ?? '',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'no-store, no-cache',
        ]);
    }

}
