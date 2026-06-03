<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal       = \App\Models\JadwalTes::orderBy('tanggal_mulai', 'desc')->paginate(15);
        $angkatanList = \App\Models\Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        return view('admin.jadwal.index', compact('jadwal', 'angkatanList'));
    }

    public function create()
    {
        $angkatanList = \App\Models\Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        return view('admin.jadwal.create', compact('angkatanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'             => 'required|string|max:100',
            'jenis_tes'        => 'nullable|in:minat,bakat',
            'angkatan'         => 'nullable|digits:4',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after:tanggal_mulai',
            'keterangan'       => 'nullable|string|max:500',
        ], [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        \App\Models\JadwalTes::create([
            'nama'            => $request->nama,
            'jenis_tes'       => $request->jenis_tes ?: null,
            'angkatan'        => $request->angkatan ?: null,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'aktif'           => $request->boolean('aktif', true),
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal tes berhasil dibuat.');
    }

    public function edit(\App\Models\JadwalTes $jadwal)
    {
        $angkatanList = \App\Models\Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->pluck('angkatan');
        return view('admin.jadwal.edit', compact('jadwal', 'angkatanList'));
    }

    public function update(Request $request, \App\Models\JadwalTes $jadwal)
    {
        $request->validate([
            'nama'            => 'required|string|max:100',
            'jenis_tes'       => 'nullable|in:minat,bakat',
            'angkatan'        => 'nullable|digits:4',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'keterangan'      => 'nullable|string|max:500',
        ], [
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $jadwal->update([
            'nama'            => $request->nama,
            'jenis_tes'       => $request->jenis_tes ?: null,
            'angkatan'        => $request->angkatan ?: null,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'aktif'           => $request->boolean('aktif'),
            'keterangan'      => $request->keterangan,
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal tes diperbarui.');
    }

    public function destroy(\App\Models\JadwalTes $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dihapus.');
    }

    public function toggleAktif(\App\Models\JadwalTes $jadwal)
    {
        $jadwal->update(['aktif' => !$jadwal->aktif]);
        $status = $jadwal->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Jadwal berhasil {$status}.");
    }
}
