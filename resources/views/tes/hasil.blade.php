@extends('layouts.app')
@section('title', 'Hasil Tes Konsentrasi')

@push('styles')
<style>
@keyframes progressFill { from { width: 0 } }
.progress-animate { animation: progressFill 1.2s ease-out forwards; }
</style>
@endpush

@section('content')
@php
$colors = match($hasil->rekomendasi) {
    'pemasaran' => ['brand-500','brand-600','brand-50','brand-500/20','brand-400','brand-200','blue-light'],
    'keuangan'  => ['success-500','success-600','success-50','success-500/20','success-400','success-200','success'],
    'sdm'       => ['warning-500','warning-600','warning-50','warning-500/20','warning-400','warning-200','warning'],
    default     => ['brand-500','brand-600','brand-50','brand-500/20','brand-400','brand-200','brand'],
};
$icon = match($hasil->rekomendasi) {
    'pemasaran' => '<path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
    'keuangan'  => '<path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
    'sdm'       => '<path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
    default     => '',
};

$allScores = [
    'Pemasaran' => ['nilai' => $hasil->nilai_pemasaran, 'key' => 'pemasaran', 'color' => 'brand'],
    'Keuangan'  => ['nilai' => $hasil->nilai_keuangan,  'key' => 'keuangan',  'color' => 'success'],
    'SDM'       => ['nilai' => $hasil->nilai_sdm,        'key' => 'sdm',       'color' => 'warning'],
];
arsort($allScores);
@endphp

{{-- Badge kedua tes selesai --}}
<div class="mb-2 flex items-center gap-2">
    @foreach(['minat'=>['Tes Minat','#465fff'],'bakat'=>['Tes Bakat','#12b76a']] as $k=>[$lbl,$c])
    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium"
        style="background:{{ $c }}15; border:1px solid {{ $c }}30; color:{{ $c }}">
        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg>
        {{ $lbl }} Selesai
    </span>
    @endforeach
</div>

<div class="space-y-4">

    {{-- Disclaimer — pindah ke atas --}}
    <div class="rounded-2xl border border-warning-200 dark:border-warning-900 bg-warning-50 dark:bg-warning-900/20 p-4">
        <div class="flex items-start gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-warning-100 dark:bg-warning-500/20 shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
                <h4 class="font-semibold text-warning-800 dark:text-warning-300 text-sm mb-1">Rekomendasi Belum Bersifat Final</h4>
                <p class="text-xs text-warning-700 dark:text-warning-400/90 leading-relaxed">
                    Rekomendasi Konsentrasi belum bersifat final. Hasil ini disusun hanya berdasarkan tes minat dan bakat.
                    Penentuan konsentrasi akhir masih perlu meninjau aspek lain seperti
                    <strong>Pilihan Konsentrasi, Nilai Mata Kuliah, Rekomendasi Penasehat Akademik, Prestasi Pendukung dan IPK</strong>.
                    Hasil akhir konsentrasi akan disampaikan nanti.
                </p>
            </div>
        </div>
    </div>

    {{-- Hero Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gray-900 dark:bg-gray-800 p-6 text-center">
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 border border-white/20 mb-5 mx-auto">
                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none">{!! $icon !!}</svg>
            </div>
            <p class="text-white/60 text-sm mb-2">Rekomendasi Konsentrasi Anda</p>
            <h1 class="text-2xl font-bold text-white mb-4">{{ $hasil->label_rekomendasi }}</h1>
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/20 px-4 py-2">
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-white font-semibold text-sm">{{ $mahasiswa->nama }}</span>
            </div>
            <p class="text-white/40 text-xs mt-3">{{ $hasil->created_at->format('d F Y, H:i') }} WITA</p>
        </div>
        {{-- Decorative circles --}}
        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-white/5"></div>
    </div>

    {{-- Score Bars --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 sm:p-6">
        <h3 class="font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Perbandingan Nilai Akhir
        </h3>
        <div class="space-y-5">
            @foreach($allScores as $label => $data)
            @php
                $isTop = $data['key'] === $hasil->rekomendasi;
                $w = number_format($data['nilai'], 1);
                $colors_map = ['pemasaran' => '#465fff', 'keuangan' => '#12b76a', 'sdm' => '#f79009'];
                $c = $colors_map[$data['key']];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $label }}</span>
                        @if($isTop)
                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium"
                            style="background: {{ $c }}20; color: {{ $c }}">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Tertinggi
                        </span>
                        @endif
                    </div>
                    <span class="text-lg font-bold" style="color: {{ $c }}">{{ number_format($data['nilai'], 2) }}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full progress-animate"
                        style="width: {{ $data['nilai'] }}%; background: {{ $c }}; animation-delay: {{ $loop->index * 200 }}ms"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 sm:p-6">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 5.5C3.25 4.26 4.26 3.25 5.5 3.25h13c1.24 0 2.25 1.01 2.25 2.25v13c0 1.24-1.01 2.25-2.25 2.25h-13C4.26 20.75 3.25 19.74 3.25 18.5v-13zm2.25-1.25a.75.75 0 00-.75.75v3.08h13.5V5c0-.41-.34-.75-.75-.75H5.5zm-1.25 5.33v3.84h3.83v-3.84H4.25zm5.33 0v3.84h3.84v-3.84h-3.84zm5.34 0v3.84h3.83v-3.84h-3.83zM4.75 18.5v-3.08H8.58v4h-3.08a.75.75 0 01-.75-.92zm5.33.92v-4H13.9v4h-3.84zm5.34 0v-4h3.83v3.08a.75.75 0 01-.75.92h-3.08z" fill="currentColor"/></svg>
            Rincian Skor
        </h3>
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
            <table class="w-full text-sm min-w-[400px]">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Konsentrasi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Minat (/75)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Bakat (/50)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach([
                        ['Pemasaran', $hasil->skor_minat_pemasaran, $hasil->skor_bakat_pemasaran, $hasil->nilai_pemasaran, 'pemasaran', '#465fff'],
                        ['Keuangan',  $hasil->skor_minat_keuangan,  $hasil->skor_bakat_keuangan,  $hasil->nilai_keuangan,  'keuangan',  '#12b76a'],
                        ['SDM',       $hasil->skor_minat_sdm,        $hasil->skor_bakat_sdm,        $hasil->nilai_sdm,        'sdm',       '#f79009'],
                    ] as [$label, $minat, $bakat, $nilai, $key, $clr])
                    <tr class="{{ $key === $hasil->rekomendasi ? 'bg-gray-50 dark:bg-gray-800/40' : '' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $clr }}"></div>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $label }}</span>
                                @if($key === $hasil->rekomendasi)
                                <svg class="w-4 h-4 text-yellow-500 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $minat }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $bakat }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-base" style="color:{{ $clr }}">{{ number_format($nilai, 2) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-3 text-xs text-gray-400 text-center">Formula: (Skor Minat / 75 × 100 × 60%) + (Skor Bakat / 50 × 100 × 40%)</p>
    </div>

    {{-- Nilai Mata Kuliah Pendukung (info pendukung, tidak memengaruhi hasil tes) --}}
    @php $mkData = $mahasiswa->nilaiMkPerKonsentrasi(); @endphp
    @if($mkData)
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-1">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 14l9-5-9-5-9 5 9 5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 14l6.16-3.42a12 12 0 01.84 4.42 12 12 0 01-7 .91 12 12 0 01-7-.91 12 12 0 01.84-4.42L12 14z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Nilai Akademik Pendukung
            </h3>
            @if($mahasiswa->ipk !== null)
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 dark:bg-brand-500/10 px-3 py-1.5 text-sm">
                <span class="text-xs text-gray-400">IPK</span>
                <span class="font-bold text-brand-600 dark:text-brand-400">{{ number_format($mahasiswa->ipk, 2) }}</span>
            </span>
            @endif
        </div>
        <p class="text-xs text-gray-400 mb-4">Rata-rata nilai mata kuliah per konsentrasi (skala 0–100). Sebagai bahan pertimbangan tambahan, tidak memengaruhi nilai tes di atas.</p>

        <div class="space-y-3">
            @foreach($mkData as $mk)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $mk['label'] }}</span>
                    <span class="text-sm font-bold" style="color:{{ $mk['warna'] }}">{{ $mk['avg'] }}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full" style="width:{{ $mk['avg'] }}%; background:{{ $mk['warna'] }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Logout --}}
    <div class="text-center pb-6">
        <form action="{{ route('logout.mahasiswa') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M15 3H7C5.895 3 5 3.895 5 5v14c0 1.105.895 2 2 2h8M19 12H9M16 9l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection
