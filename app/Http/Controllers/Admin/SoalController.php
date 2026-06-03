<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    const MIN_MINAT = 5;
    const MIN_BAKAT = 3;

    private function getStatusSoal(): array
    {
        $status = [];
        foreach (['pemasaran', 'keuangan', 'sdm'] as $k) {
            $minat = \App\Models\Soal::where('aktif', true)->where('jenis', 'minat')->where('konsentrasi', $k)->count();
            $bakat = \App\Models\Soal::where('aktif', true)->where('jenis', 'bakat')->where('konsentrasi', $k)->count();
            $status[$k] = [
                'minat'     => $minat,
                'bakat'     => $bakat,
                'ok_minat'  => $minat >= self::MIN_MINAT,
                'ok_bakat'  => $bakat >= self::MIN_BAKAT,
            ];
        }
        return $status;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Soal::query();
        if ($request->jenis) $query->where('jenis', $request->jenis);
        if ($request->konsentrasi) $query->where('konsentrasi', $request->konsentrasi);
        $soal       = $query->orderBy('konsentrasi')->orderBy('jenis')->orderBy('urutan')->paginate(20);
        $statusSoal = $this->getStatusSoal();
        return view('admin.soal.index', compact('soal', 'statusSoal'));
    }

    public function create()
    {
        return view('admin.soal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'teks'        => 'required',
            'jenis'       => 'required|in:minat,bakat',
            'konsentrasi' => 'required|in:pemasaran,keuangan,sdm',
        ]);

        \App\Models\Soal::create($request->only('teks', 'jenis', 'konsentrasi', 'urutan', 'aktif'));
        $this->clearSoalCache();
        return redirect()->route('admin.soal.index')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(\App\Models\Soal $soal)
    {
        return view('admin.soal.edit', compact('soal'));
    }

    public function update(Request $request, \App\Models\Soal $soal)
    {
        $request->validate([
            'teks'        => 'required',
            'jenis'       => 'required|in:minat,bakat',
            'konsentrasi' => 'required|in:pemasaran,keuangan,sdm',
        ]);

        $soal->update($request->only('teks', 'jenis', 'konsentrasi', 'urutan') + ['aktif' => $request->boolean('aktif')]);
        $this->clearSoalCache();
        return redirect()->route('admin.soal.index')->with('success', 'Soal diperbarui.');
    }

    public function destroy(\App\Models\Soal $soal)
    {
        $k     = $soal->konsentrasi;
        $j     = $soal->jenis;
        $min   = $j === 'minat' ? self::MIN_MINAT : self::MIN_BAKAT;
        $count = \App\Models\Soal::where('aktif', true)->where('jenis', $j)->where('konsentrasi', $k)->count();

        if ($count <= $min) {
            return redirect()->back()->with('error',
                "Tidak bisa menghapus. Soal {$j} konsentrasi ".ucfirst($k)." minimal {$min} butir aktif. Saat ini hanya ada {$count}."
            );
        }

        $soal->delete();
        $this->clearSoalCache();
        return redirect()->route('admin.soal.index')->with('success', 'Soal dihapus.');
    }

    private function clearSoalCache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('soal_aktif_minat');
        \Illuminate\Support\Facades\Cache::forget('soal_aktif_bakat');
    }
}
