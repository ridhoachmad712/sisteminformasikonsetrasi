<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginMahasiswa()
    {
        return view('auth.login-mahasiswa');
    }

    /**
     * Langkah 1: Mahasiswa input NIM → sistem cari & tampilkan nama untuk konfirmasi.
     * Kembalikan JSON agar bisa ditampilkan secara dinamis tanpa reload halaman.
     */
    public function cekNim(Request $request)
    {
        $request->validate(['nim' => 'required|string']);

        $mahasiswa = \App\Models\Mahasiswa::where('nim', trim($request->nim))
            ->select('id', 'nim', 'nama', 'angkatan', 'aktif')
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'found' => false,
                'message' => 'NIM tidak ditemukan. Pastikan NIM Anda benar.',
            ]);
        }

        if (!$mahasiswa->aktif) {
            return response()->json([
                'found' => false,
                'message' => 'Akun Anda tidak aktif. Hubungi Prodi Manajemen untuk informasi lebih lanjut.',
            ]);
        }

        // Simpan NIM yang sedang dikonfirmasi di session sementara
        session(['confirm_nim' => $mahasiswa->nim]);

        return response()->json([
            'found'    => true,
            'nama'     => $mahasiswa->nama,
            'angkatan' => $mahasiswa->angkatan,
            'nim'      => $mahasiswa->nim,
        ]);
    }

    /**
     * Langkah 2: Mahasiswa klik "Ya, ini saya" → login.
     */
    public function loginMahasiswa(Request $request)
    {
        $request->validate(['nim' => 'required|string']);

        $nim = trim($request->nim);

        // Validasi: NIM yang dikonfirmasi harus cocok dengan yang ada di session sementara
        if (session('confirm_nim') !== $nim) {
            return back()->withErrors(['nim' => 'Sesi konfirmasi tidak valid. Silakan coba lagi.']);
        }

        $mahasiswa = \App\Models\Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return back()->withErrors(['nim' => 'NIM tidak ditemukan.']);
        }

        if (!$mahasiswa->aktif) {
            return back()->withErrors(['nim' => 'Akun tidak aktif. Hubungi Prodi Manajemen.']);
        }

        // Hapus session sementara konfirmasi
        session()->forget('confirm_nim');

        // Generate token sesi (cegah login ganda)
        $token = \Illuminate\Support\Str::random(60);
        $mahasiswa->update(['session_token' => $token]);

        session([
            'mahasiswa_id'    => $mahasiswa->id,
            'mahasiswa_nama'  => $mahasiswa->nama,
            'mahasiswa_token' => $token,
        ]);

        return redirect()->route('beranda');
    }

    public function showLoginAdmin()
    {
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function logoutMahasiswa(Request $request)
    {
        $id = session('mahasiswa_id');
        if ($id) {
            \App\Models\Mahasiswa::where('id', $id)->update(['session_token' => null]);
        }
        $request->session()->forget(['mahasiswa_id', 'mahasiswa_nama', 'mahasiswa_token']);
        return redirect()->route('login.mahasiswa');
    }

    public function logoutAdmin(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.admin');
    }
}
