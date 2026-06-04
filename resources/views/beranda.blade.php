@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
@php
use Carbon\Carbon;
$now = now();

$statusMinat = match(true) {
    $mahasiswa->sudah_tes_minat          => 'selesai',
    !$jadwalMinat                        => 'belum_dijadwalkan',
    !$jadwalMinat->aktif                 => 'nonaktif',
    $jadwalMinat->belum_mulai            => 'belum_mulai',
    $jadwalMinat->sudah_berakhir         => 'sudah_berakhir',
    default                              => 'berlangsung',
};
$statusBakat = match(true) {
    $mahasiswa->sudah_tes_bakat          => 'selesai',
    !$jadwalBakat                        => 'belum_dijadwalkan',
    !$jadwalBakat->aktif                 => 'nonaktif',
    $jadwalBakat->belum_mulai            => 'belum_mulai',
    $jadwalBakat->sudah_berakhir         => 'sudah_berakhir',
    default                              => 'berlangsung',
};

$done         = (int)$mahasiswa->sudah_tes_minat + (int)$mahasiswa->sudah_tes_bakat;
$keduaSelesai = $mahasiswa->sudah_tes_minat && $mahasiswa->sudah_tes_bakat;
$tesTerkunci  = !$mahasiswa->sudah_input_nilai || !$mahasiswa->sudah_pilih_konsentrasi;

$jam  = (int)$now->format('H');
$sapa = $jam >= 5 && $jam < 12 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 19 ? 'Selamat sore' : 'Selamat malam'));
@endphp

<div class="space-y-4">

    {{-- ── Greeting ──────────────────────────────────────────── --}}
    <div class="rounded-2xl bg-gray-900 dark:bg-gray-800 p-6 relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-gray-400 text-sm mb-1">{{ $sapa }},</p>
            <h1 class="text-white text-xl font-bold leading-snug">{{ $mahasiswa->nama }}</h1>
            <p class="text-gray-500 text-xs mt-1">NIM {{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</p>

            {{-- Progress bar --}}
            <div class="mt-4">
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="text-gray-400">Progress tes</span>
                    <span class="text-gray-300 font-medium">{{ $done }}/2 selesai</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full bg-brand-400 transition-all duration-700"
                        style="width: {{ $done * 50 }}%"></div>
                </div>
            </div>
        </div>
        <div class="absolute -top-6 -right-6 w-28 h-28 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-8 -left-4 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    {{-- ── Hasil tersedia ─────────────────────────────────────── --}}
    @if($keduaSelesai && $hasil?->lengkap)
    @php
        $rc  = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$hasil->rekomendasi] ?? '#6b7280';
        $rek = $hasil->label_rekomendasi;
    @endphp
    <div class="rounded-2xl border p-5 flex items-center gap-4"
        style="border-color:{{ $rc }}30; background:{{ $rc }}08">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl text-white text-xl shrink-0"
            style="background:{{ $rc }}">🎓</div>
        <div class="flex-1 min-w-0">
            <p class="text-xs text-gray-400 mb-0.5">Rekomendasi konsentrasi Anda</p>
            <p class="font-bold text-gray-900 dark:text-white truncate" style="color:{{ $rc }}">{{ $rek }}</p>
        </div>
        <a href="{{ route('tes.hasil') }}"
            class="shrink-0 text-xs font-semibold px-3 py-2 rounded-lg text-white transition-colors"
            style="background:{{ $rc }}">
            Lihat →
        </a>
    </div>
    @endif

    {{-- ── Menu utama ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

        {{-- Kartu Pilihan Konsentrasi --}}
        <a href="{{ route('pilihan.index') }}"
            class="group sm:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 hover:border-brand-300 dark:hover:border-brand-800 hover:shadow-theme-sm transition-all">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 shrink-0">
                    <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M7 12h10M10 18h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Pilihan Konsentrasi</h3>
                    <p class="text-xs text-gray-400 leading-relaxed">Urutkan preferensi konsentrasi Anda (peringkat 1–2–3).</p>
                    @if($mahasiswa->sudah_pilih_konsentrasi && $mahasiswa->pilihan_konsentrasi)
                    <div class="flex flex-wrap items-center gap-1.5 mt-2">
                        @foreach($mahasiswa->pilihan_konsentrasi as $i => $k)
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-xs font-medium text-gray-600 dark:text-gray-300">
                            <span class="text-brand-500 font-bold">{{ $i + 1 }}.</span> {{ \App\Models\Mahasiswa::labelKonsentrasi($k) }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="shrink-0">
                    @if($mahasiswa->sudah_pilih_konsentrasi)
                    <span class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">Sudah ✓</span>
                    @else
                    <span class="inline-flex items-center rounded-full bg-warning-100 dark:bg-warning-500/20 px-2 py-0.5 text-xs font-medium text-warning-700 dark:text-warning-400">Belum diisi</span>
                    @endif
                </div>
            </div>
        </a>

        {{-- Kartu Nilai Mata Kuliah --}}
        <a href="{{ route('nilai.index') }}"
            class="group rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 hover:border-brand-300 dark:hover:border-brand-800 hover:shadow-theme-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10">
                    <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 14l9-5-9-5-9 5 9 5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 14l6.16-3.42a12 12 0 01.84 4.42 12 12 0 01-7 .91 12 12 0 01-7-.91 12 12 0 01.84-4.42L12 14z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-700 group-hover:text-brand-400 transition-colors" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Akademik</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Input IPK, Dosen Penasehat Akademik dan Mata Kuliah Pendukung Konsentrasi.</p>
            <div class="mt-3">
                @if($mahasiswa->sudah_input_nilai)
                <span class="inline-flex items-center gap-1 rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">
                    Sudah diisi ✓
                </span>
                @else
                <span class="inline-flex items-center gap-1 rounded-full bg-warning-100 dark:bg-warning-500/20 px-2 py-0.5 text-xs font-medium text-warning-700 dark:text-warning-400">
                    Belum diisi
                </span>
                @endif
            </div>
        </a>

        {{-- Kartu Tes --}}
        @if($tesTerkunci)
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 p-5 opacity-60 cursor-not-allowed">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800">
                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-700" viewBox="0 0 24 24" fill="none"><path d="M12 17v-6M12 7h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            </div>
            <h3 class="font-semibold text-gray-400 dark:text-gray-500 text-sm mb-1">Tes Konsentrasi</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Kerjakan tes minat dan bakat untuk mendapatkan rekomendasi konsentrasi.</p>
            <div class="mt-3 flex flex-col gap-1">
                @if(!$mahasiswa->sudah_input_nilai)
                <p class="text-xs text-warning-600 dark:text-warning-400 flex items-center gap-1">
                    <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Selesaikan input Akademik terlebih dahulu
                </p>
                @endif
                @if(!$mahasiswa->sudah_pilih_konsentrasi)
                <p class="text-xs text-warning-600 dark:text-warning-400 flex items-center gap-1">
                    <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Selesaikan Pilihan Konsentrasi terlebih dahulu
                </p>
                @endif
            </div>
        </div>
        @else
        <a href="{{ route('tes.index') }}"
            class="group rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 hover:border-brand-300 dark:hover:border-brand-800 hover:shadow-theme-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10">
                    <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-700 group-hover:text-brand-400 transition-colors" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Tes Konsentrasi</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Kerjakan tes minat dan bakat untuk mendapatkan rekomendasi konsentrasi.</p>

            {{-- Status badge --}}
            <div class="flex gap-1.5 mt-3">
                @foreach(['Minat' => $statusMinat, 'Bakat' => $statusBakat] as $lbl => $st)
                @php
                    [$bgSt, $txtSt] = match($st) {
                        'selesai'    => ['bg-success-100 dark:bg-success-500/20', 'text-success-600 dark:text-success-400'],
                        'berlangsung'=> ['bg-brand-100 dark:bg-brand-500/20',   'text-brand-600 dark:text-brand-400'],
                        default      => ['bg-gray-100 dark:bg-gray-800',         'text-gray-400'],
                    };
                    $dot = $st === 'berlangsung';
                @endphp
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $bgSt }} {{ $txtSt }}">
                    @if($dot)<span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>@endif
                    {{ $lbl }}
                    @if($st === 'selesai') ✓ @endif
                </span>
                @endforeach
            </div>
        </a>
        @endif

        {{-- Kartu Hasil Konsentrasi Final --}}
        <div class="sm:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 shrink-0">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" viewBox="0 0 24 24" fill="none">
                        <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Hasil Konsentrasi</h3>
                    <p class="text-xs text-gray-400 leading-relaxed mt-0.5">
                        Hasil konsentrasi final dapat dilihat setelah hasil perhitungan dan validasi dilakukan oleh Prodi.
                    </p>
                </div>
                <span class="shrink-0 inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-400 dark:text-gray-500">
                    Belum tersedia
                </span>
            </div>
        </div>

    </div>


</div>
@endsection
