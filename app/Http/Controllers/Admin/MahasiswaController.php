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
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nim', 'like', "%{$request->search}%");
            });
        }
        if ($request->angkatan) {
            $query->where('angkatan', $request->angkatan);
        }
        if ($request->status_tes) {
            match($request->status_tes) {
                'belum'   => $query->where('sudah_tes', false)->where('sudah_tes_minat', false)->where('sudah_tes_bakat', false),
                'sebagian'=> $query->where(fn($q) => $q->where('sudah_tes_minat', true)->orWhere('sudah_tes_bakat', true))->where('sudah_tes', false),
                'selesai' => $query->where('sudah_tes', true),
                default   => null,
            };
        }

        $mahasiswa    = $query->orderBy('angkatan', 'desc')->orderBy('nama')->paginate(20)->withQueryString();
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
        // Hapus semua hasil tes & detail jawaban (cascade)
        $mahasiswum->hasilTes()->each(fn($h) => $h->detailJawaban()->delete());
        $mahasiswum->hasilTes()->delete();

        // Reset semua kolom terkait tes
        $mahasiswum->update([
            'sudah_tes'        => false,
            'sudah_tes_minat'  => false,
            'sudah_tes_bakat'  => false,
            'draft_minat'      => null,
            'draft_bakat'      => null,
            'urutan_minat'     => null,
            'urutan_bakat'     => null,
            'tes_aktif'        => null,
            'last_activity_at' => null,
        ]);

        return redirect()->back()->with('success', 'Status tes ' . $mahasiswum->nama . ' berhasil direset.');
    }

    public function toggleAktif(\App\Models\Mahasiswa $mahasiswum)
    {
        $mahasiswum->update(['aktif' => !$mahasiswum->aktif]);
        $status = $mahasiswum->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', $mahasiswum->nama . ' berhasil ' . $status . '.');
    }

    public function bulkToggleAktif(Request $request)
    {
        $action = $request->input('action'); // 'aktifkan' atau 'nonaktifkan'
        $aktif  = $action === 'aktifkan';

        \App\Models\Mahasiswa::query()->update(['aktif' => $aktif]);

        $total  = \App\Models\Mahasiswa::count();
        $label  = $aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Semua {$total} mahasiswa berhasil {$label}.");
    }
}
