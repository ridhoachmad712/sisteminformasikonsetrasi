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

        {{-- Kartu Tes --}}
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

        {{-- Kartu Nilai Mata Kuliah --}}
        <a href="{{ route('nilai.index') }}"
            class="group rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 hover:border-brand-300 dark:hover:border-brand-800 hover:shadow-theme-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10">
                    <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 14l9-5-9-5-9 5 9 5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 14l6.16-3.42a12 12 0 01.84 4.42 12 12 0 01-7 .91 12 12 0 01-7-.91 12 12 0 01.84-4.42L12 14z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-700 group-hover:text-brand-400 transition-colors" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Nilai Akademik</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Input IPK & 9 nilai mata kuliah pendukung konsentrasi.</p>
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

        {{-- Kartu Profil --}}
        <a href="{{ route('profil.index') }}"
            class="group rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 hover:border-gray-300 dark:hover:border-gray-700 hover:shadow-theme-sm transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.306 3.5 3.5 7.306 3.5 12c0 2.153.8 4.118 2.119 5.616.553-2.306 2.629-4.021 5.105-4.021h2.55c2.476 0 4.552 1.715 5.105 4.021C19.699 16.118 20.5 14.153 20.5 12c0-4.694-3.806-8.5-8.5-8.5zM12 12.786c1.114 0 2.017-.903 2.017-2.018 0-1.114-.903-2.017-2.017-2.017-1.115 0-2.018.903-2.018 2.017 0 1.115.903 2.018 2.018 2.018z" fill="currentColor"/>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-700 group-hover:text-gray-500 transition-colors" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Profil Saya</h3>
            <p class="text-xs text-gray-400 leading-relaxed">Lihat data diri dan ringkasan hasil tes Anda.</p>
        </a>

    </div>

    {{-- ── Info jadwal (jika ada yang berlangsung/segera) ────── --}}
    @php
        $jadwalTampil = collect([$jadwalMinat, $jadwalBakat])
            ->filter(fn($j) => $j && !$j->sudah_berakhir)
            ->unique('id');
    @endphp
    @if($jadwalTampil->isNotEmpty())
    <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800 overflow-hidden">
        <div class="px-5 py-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Jadwal Tes</p>
        </div>
        @foreach($jadwalTampil as $j)
        @php
            $isBerlangsung = $j->sedang_berlangsung;
            $jenisSudah    = ($j->jenis_tes === 'minat' && $mahasiswa->sudah_tes_minat)
                          || ($j->jenis_tes === 'bakat' && $mahasiswa->sudah_tes_bakat);
        @endphp
        <div class="px-5 py-4 flex items-center gap-4">
            <div class="w-2 h-2 rounded-full shrink-0 {{ $isBerlangsung ? 'bg-success-500 animate-pulse' : 'bg-gray-300 dark:bg-gray-700' }}"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $j->nama }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $j->tanggal_mulai->format('d M Y, H:i') }} — {{ $j->tanggal_selesai->format('d M Y, H:i') }} WITA
                </p>
            </div>
            @if($jenisSudah)
            <span class="shrink-0 text-xs font-medium text-success-600 dark:text-success-400 bg-success-50 dark:bg-success-500/10 rounded-full px-2.5 py-1">Selesai ✓</span>
            @elseif($isBerlangsung)
            <span class="shrink-0 text-xs font-medium text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-500/10 rounded-full px-2.5 py-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>Berlangsung
            </span>
            @else
            <span class="shrink-0 text-xs text-gray-400">Belum mulai</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Panduan singkat (hanya tampil jika belum mulai tes) ── --}}
    @if($done === 0)
    <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Cara mengikuti tes</p>
        <div class="space-y-3">
            @foreach([
                ['1', 'Buka menu Tes Konsentrasi', 'Lihat status Tes Minat dan Tes Bakat.'],
                ['2', 'Kerjakan sesuai jadwal', 'Setiap tes hanya bisa dikerjakan satu kali.'],
                ['3', 'Selesaikan keduanya', 'Hasil rekomendasi muncul setelah kedua tes selesai.'],
            ] as [$no, $judul, $desc])
            <div class="flex items-start gap-3">
                <div class="flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-bold shrink-0 mt-0.5">{{ $no }}</div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $judul }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
