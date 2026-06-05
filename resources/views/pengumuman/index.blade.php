@extends('layouts.app')
@section('title', 'Pengumuman Konsentrasi')

@section('content')
@php
$jam  = (int)now()->format('H');
$sapa = $jam >= 5 && $jam < 12 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 19 ? 'Selamat sore' : 'Selamat malam'));
@endphp

<div class="space-y-5">

    {{-- ── Greeting ──────────────────────────────────────────── --}}
    <div class="rounded-2xl bg-gray-900 dark:bg-gray-800 p-6">
        <p class="text-gray-400 text-sm mb-1">{{ $sapa }},</p>
        <h1 class="text-white text-xl font-bold leading-snug">{{ $mahasiswa->nama }}</h1>
        <p class="text-gray-500 text-xs mt-1">NIM {{ $mahasiswa->nim }} &middot; Angkatan {{ $mahasiswa->angkatan }}</p>
        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1">
            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
            <span class="text-gray-300 text-xs">Hasil seleksi konsentrasi telah tersedia</span>
        </div>
    </div>

    {{-- ── Card CTA ───────────────────────────────────────────── --}}
    @if($mahasiswa->hasil_final)

    {{-- Loading overlay --}}
    <div id="loading-overlay"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white dark:bg-gray-950 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="space-y-5 text-center px-8">
            <div class="flex justify-center">
                <svg class="w-10 h-10 text-brand-500 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                    <path class="opacity-90" d="M12 2a10 10 0 0110 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Membuka pengumuman...</p>
                <p class="text-xs text-gray-400 mt-1">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>

    <a href="{{ route('pengumuman.hasil') }}" id="btn-hasil"
        class="group flex items-center justify-between gap-4 rounded-2xl border border-brand-200 dark:border-brand-800 bg-white dark:bg-gray-900 p-5 transition-all hover:shadow-md active:scale-[0.98]">
        <div>
            <p class="text-xs text-brand-500 font-medium uppercase tracking-wide mb-1">Pengumuman Resmi</p>
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Lihat Hasil Seleksi Konsentrasi</h2>
            <p class="text-xs text-gray-400 mt-0.5">Hasil telah divalidasi oleh Prodi Manajemen</p>
        </div>
        <div class="shrink-0 flex items-center justify-center w-9 h-9 rounded-full bg-brand-50 dark:bg-brand-500/20 transition-transform group-hover:translate-x-1">
            <svg class="w-4 h-4 text-brand-500" viewBox="0 0 24 24" fill="none">
                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </a>

    <p class="text-xs text-center text-gray-400">Hasil seleksi bersifat final dan tidak dapat diganggu gugat.</p>

    <script>
        document.getElementById('btn-hasil').addEventListener('click', function(e) {
            e.preventDefault();
            const overlay = document.getElementById('loading-overlay');
            const href    = this.href;
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
            setTimeout(() => { window.location.href = href; }, 1200);
        });
    </script>

    @else
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 text-center">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Hasil Belum Tersedia</h2>
        <p class="text-xs text-gray-400 leading-relaxed">
            Hasil seleksi konsentrasi Anda sedang dalam proses validasi oleh Prodi Manajemen. Silakan cek kembali nanti.
        </p>
    </div>
    @endif

</div>
@endsection
