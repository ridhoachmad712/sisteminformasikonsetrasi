<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMahasiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    /**
     * Token disimpan di session sehingga tidak perlu query DB di setiap request.
     * Verifikasi ke DB hanya dilakukan setiap 5 menit (lazy check) untuk
     * mendeteksi login dari perangkat lain tanpa membebani database.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id    = session('mahasiswa_id');
        $token = session('mahasiswa_token');

        if (!$id || !$token) {
            return redirect()->route('login.mahasiswa')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek ke DB hanya setiap 5 menit (bukan setiap request)
        $lastCheck = session('token_last_check', 0);
        if (time() - $lastCheck > 300) {
            $mahasiswa = \App\Models\Mahasiswa::select('id', 'session_token')
                ->find($id);

            if (!$mahasiswa || $mahasiswa->session_token !== $token) {
                session()->forget(['mahasiswa_id', 'mahasiswa_nama', 'mahasiswa_token', 'token_last_check']);
                return redirect()->route('login.mahasiswa')
                    ->with('error', 'Sesi Anda telah berakhir karena login dari perangkat lain.');
            }

            session(['token_last_check' => time()]);
        }

        return $next($request);
    }
}
