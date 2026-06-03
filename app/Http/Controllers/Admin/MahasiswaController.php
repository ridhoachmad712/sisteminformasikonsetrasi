<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Mahasiswa::query();
        if ($request->search) {
            $query->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nim', 'like', "%{$request->search}%");
        }
        if ($request->angkatan) {
            $query->where('angkatan', $request->angkatan);
        }
        $mahasiswa = $query->orderBy('angkatan', 'desc')->orderBy('nama')->paginate(20);
        $angkatanList = \App\Models\Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        return view('admin.mahasiswa.index', compact('mahasiswa', 'angkatanList'));
    }

    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim'      => 'required|unique:mahasiswa,nim',
            'nama'     => 'required',
            'angkatan' => 'required|digits:4',
            'email'    => 'nullable|email',
            'password' => 'required|min:6',
        ]);

        \App\Models\Mahasiswa::create([
            'nim'      => $request->nim,
            'nama'     => $request->nama,
            'angkatan' => $request->angkatan,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(\App\Models\Mahasiswa $mahasiswum)
    {
        return view('admin.mahasiswa.edit', ['mahasiswa' => $mahasiswum]);
    }

    public function update(Request $request, \App\Models\Mahasiswa $mahasiswum)
    {
        $request->validate([
            'nim'      => 'required|unique:mahasiswa,nim,' . $mahasiswum->id,
            'nama'     => 'required',
            'angkatan' => 'required|digits:4',
            'email'    => 'nullable|email',
        ]);

        $data = $request->only('nim', 'nama', 'angkatan', 'email');
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $mahasiswum->update($data);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa diperbarui.');
    }

    public function destroy(\App\Models\Mahasiswa $mahasiswum)
    {
        $mahasiswum->delete();
        return redirect()->route('admin.mahasiswa.index')->with('success', 'Mahasiswa dihapus.');
    }

    public function resetTes(\App\Models\Mahasiswa $mahasiswum)
    {
        $mahasiswum->update(['sudah_tes' => false]);
        $mahasiswum->hasilTes()->delete();
        return redirect()->back()->with('success', 'Status tes mahasiswa berhasil direset.');
    }
}
