@extends('layouts.app')
@section('title', 'Tes Konsentrasi')

@section('content')
@php
use Carbon\Carbon;
$now = now();

// Status untuk masing-masing tes
$statusMinat = match(true) {
    $mahasiswa->sudah_tes_minat => 'selesai',
    !$jadwalMinat              => 'belum_dijadwalkan',
    !$jadwalMinat->aktif       => 'nonaktif',
    $jadwalMinat->belum_mulai  => 'belum_mulai',
    $jadwalMinat->sudah_berakhir => 'sudah_berakhir',
    default                    => 'berlangsung',
};

$statusBakat = match(true) {
    $mahasiswa->sudah_tes_bakat => 'selesai',
    !$jadwalBakat              => 'belum_dijadwalkan',
    !$jadwalBakat->aktif       => 'nonaktif',
    $jadwalBakat->belum_mulai  => 'belum_mulai',
    $jadwalBakat->sudah_berakhir => 'sudah_berakhir',
    default                    => 'berlangsung',
};

$kedua_selesai = $mahasiswa->sudah_tes_minat && $mahasiswa->sudah_tes_bakat;
@endphp

<div class="space-y-4">

    {{-- Header --}}
    <div class="rounded-2xl bg-gray-900 dark:bg-gray-800 p-6 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 shrink-0">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-white text-base">Tes Konsentrasi Prodi Manajemen</h1>
                    <p class="text-white/50 text-xs">Halo, {{ $mahasiswa->nama }} · Angkatan {{ $mahasiswa->angkatan }}</p>
                </div>
            </div>

            {{-- Progress --}}
            @php $done = (int)$mahasiswa->sudah_tes_minat + (int)$mahasiswa->sudah_tes_bakat; @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-white/10 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full bg-brand-400 transition-all duration-500" style="width:{{ $done * 50 }}%"></div>
                </div>
                <span class="text-white/70 text-xs font-medium shrink-0">{{ $done }}/2 tes selesai</span>
            </div>
        </div>
        <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-6 -left-6 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    {{-- Hasil siap ditampilkan --}}
    @if($kedua_selesai)
    <div class="rounded-2xl border border-success-200 dark:border-success-900 bg-success-50 dark:bg-success-900/20 p-5 flex items-center gap-4">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-success-100 dark:bg-success-500/20 shrink-0">
            <svg class="w-6 h-6 text-success-600 dark:text-success-400" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg>
        </div>
        <div class="flex-1">
            <p class="font-bold text-success-700 dark:text-success-400">Kedua tes telah selesai!</p>
            <p class="text-xs text-success-600 dark:text-success-500 mt-0.5">Rekomendasi konsentrasi Anda sudah tersedia.</p>
        </div>
        <a href="{{ route('tes.hasil') }}"
            class="shrink-0 inline-flex items-center gap-1.5 rounded-xl bg-success-600 hover:bg-success-700 px-4 py-2 text-sm font-semibold text-white transition-colors">
            Lihat Hasil
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    @endif

    {{-- Card Tes Minat --}}
    <x-tes-card
        jenis="minat"
        judul="Tes Minat"
        deskripsi="Mengukur ketertarikan Anda terhadap aktivitas di bidang Pemasaran, Keuangan, dan SDM."
        :status="$statusMinat"
        :jadwal="$jadwalMinat"
        :mahasiswa="$mahasiswa"
        icon_color="#465fff"
        route_name="tes.minat"
    />

    {{-- Card Tes Bakat --}}
    <x-tes-card
        jenis="bakat"
        judul="Tes Bakat"
        deskripsi="Mengukur kemampuan dan potensi Anda yang relevan dengan konsentrasi yang tersedia."
        :status="$statusBakat"
        :jadwal="$jadwalBakat"
        :mahasiswa="$mahasiswa"
        icon_color="#12b76a"
        route_name="tes.bakat"
    />

    {{-- Catatan --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
        <p class="font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Cara Pengerjaan:</p>
        <ul class="space-y-1 list-disc list-inside">
            <li>Tes Minat dan Tes Bakat dapat dikerjakan sesuai jadwal yang ditentukan.</li>
            <li>Kedua tes harus diselesaikan untuk mendapatkan rekomendasi konsentrasi.</li>
            <li>Setiap tes hanya dapat dikerjakan <strong>satu kali</strong>.</li>
            <li>Jawaban tersimpan otomatis — aman jika browser ditutup sementara.</li>
        </ul>
    </div>

</div>
@endsection
