<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DosenPa;
use Illuminate\Http\Request;

class DosenPaController extends Controller
{
    public function index(Request $request)
    {
        $query = DosenPa::withCount('mahasiswa');

        if ($search = $request->input('search')) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
        }

        $dosenList = $query->orderBy('nama')->paginate(20)->withQueryString();

        return view('admin.dosen-pa.index', compact('dosenList', 'search'));
    }

    public function create()
    {
        return view('admin.dosen-pa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nip'  => ['nullable', 'string', 'max:30', 'unique:dosen_pa,nip'],
            'aktif' => ['boolean'],
        ], [
            'nama.required' => 'Nama dosen wajib diisi.',
            'nip.unique'    => 'NIP sudah terdaftar.',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);

        DosenPa::create($data);

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', 'Dosen PA berhasil ditambahkan.');
    }

    public function edit(DosenPa $dosenPa)
    {
        return view('admin.dosen-pa.edit', compact('dosenPa'));
    }

    public function update(Request $request, DosenPa $dosenPa)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nip'  => ['nullable', 'string', 'max:30', 'unique:dosen_pa,nip,' . $dosenPa->id],
            'aktif' => ['boolean'],
        ], [
            'nama.required' => 'Nama dosen wajib diisi.',
            'nip.unique'    => 'NIP sudah terdaftar.',
        ]);

        $data['aktif'] = $request->boolean('aktif');

        $dosenPa->update($data);

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', 'Data dosen PA berhasil diperbarui.');
    }

    public function destroy(DosenPa $dosenPa)
    {
        if ($dosenPa->mahasiswa()->exists()) {
            return back()->with('error', 'Dosen PA tidak dapat dihapus karena masih memiliki mahasiswa terdaftar.');
        }

        $dosenPa->delete();

        return redirect()->route('admin.dosen-pa.index')
            ->with('success', 'Dosen PA berhasil dihapus.');
    }
}
