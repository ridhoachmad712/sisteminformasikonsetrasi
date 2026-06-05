<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function edit(Mahasiswa $mahasiswum)
    {
        $prestasi = $mahasiswum->prestasi_relevan ?? ['pemasaran' => 0, 'keuangan' => 0, 'sdm' => 0];
        return view('admin.prestasi.edit', [
            'mahasiswa' => $mahasiswum,
            'prestasi'  => $prestasi,
        ]);
    }

    public function update(Request $request, Mahasiswa $mahasiswum)
    {
        $request->validate([
            'prestasi.pemasaran' => 'required|integer|min:0|max:15',
            'prestasi.keuangan'  => 'required|integer|min:0|max:15',
            'prestasi.sdm'       => 'required|integer|min:0|max:15',
            'catatan'            => 'nullable|string|max:500',
        ], [
            'prestasi.*.max' => 'Skor prestasi maksimal 15.',
            'prestasi.*.min' => 'Skor prestasi minimal 0.',
        ]);

        $mahasiswum->update([
            'prestasi_relevan' => [
                'pemasaran' => (int) $request->input('prestasi.pemasaran'),
                'keuangan'  => (int) $request->input('prestasi.keuangan'),
                'sdm'       => (int) $request->input('prestasi.sdm'),
            ],
            'catatan_prestasi' => $request->catatan,
        ]);

        return redirect()->route('admin.rekap.show', $mahasiswum)
            ->with('success', 'Skor prestasi relevan berhasil disimpan.');
    }
}
