<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        // Auto-save draft: max 2x per menit per mahasiswa
        // (setara 1 request per 30 detik, cukup longgar untuk UX)
        RateLimiter::for('auto-save', function ($request) {
            $id = session('mahasiswa_id', $request->ip());
            return Limit::perMinute(2)
                ->by("draft_{$id}")
                ->response(fn() => response()->json([
                    'ok'     => false,
                    'reason' => 'Terlalu sering menyimpan, coba lagi sebentar.',
                ], 429));
        });

        // Submit tes: max 1x per 5 menit per mahasiswa (anti double-submit)
        RateLimiter::for('tes-submit', function ($request) {
            $id = session('mahasiswa_id', $request->ip());
            return Limit::perMinutes(5, 1)->by("submit_{$id}");
        });

        // Login mahasiswa: max 10 percobaan per menit per IP (anti brute-force)
        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(10)->by($request->ip());
        });
    }
}
