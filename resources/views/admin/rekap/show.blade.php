@extends('layouts.admin')
@section('title', 'Detail Rekap')
@section('page-title', 'Detail Rekap Konsentrasi')

@section('content')
@php
    $hasil = $mahasiswa->hasilTesTerakhir;
    $rc    = $hasil ? (['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$hasil->rekomendasi] ?? '#6b7280') : '#6b7280';
@endphp

<a href="{{ route('admin.rekap.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Kembali ke Rekap
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Identitas --}}
    <div class="lg:col-span-3 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl text-white text-xl font-bold shrink-0" style="background:{{ $rc }}">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-gray-900 dark:text-white text-lg">{{ $mahasiswa->nama }}</h2>
                <p class="text-sm text-gray-400">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</p>
            </div>
        </div>
    </div>

    {{-- 1. Pilihan Konsentrasi --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">1. Pilihan Konsentrasi</h3>
        @if($mahasiswa->sudah_pilih_konsentrasi && $mahasiswa->pilihan_konsentrasi)
            <div class="space-y-2">
                @foreach($mahasiswa->pilihan_konsentrasi as $i => $k)
                <div class="flex items-center gap-2.5 text-sm">
                    <span class="flex items-center justify-center w-6 h-6 rounded-md bg-brand-500 text-white text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ \App\Models\Mahasiswa::labelKonsentrasi($k) }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum dipilih</p>
        @endif
    </div>

    {{-- 2. IPK --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">2. IPK</h3>
        @if($mahasiswa->ipk !== null)
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-bold text-brand-500">{{ number_format($mahasiswa->ipk, 2) }}</span>
                <span class="text-sm text-gray-400">/ 4.00</span>
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum diisi</p>
        @endif
    </div>

    {{-- 3. Nilai Mata Kuliah --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">3. Nilai Mata Kuliah</h3>
        @if($mkData)
            <div class="space-y-2">
                @foreach($mkData as $mk)
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600 dark:text-gray-400">{{ $mk['label'] }}</span>
                    <span class="font-bold rounded-md px-2 py-0.5" style="background:{{ $mk['warna'] }}15; color:{{ $mk['warna'] }}">{{ $mk['avg'] }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum diisi</p>
        @endif
    </div>

    {{-- 4. Tes Minat & Bakat --}}
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">4. Tes Minat &amp; Bakat</h3>
        @if($hasil && $hasil->lengkap)
            <div class="space-y-3">
                @foreach(['Pemasaran'=>[$hasil->nilai_pemasaran,'#465fff','pemasaran'],'Keuangan'=>[$hasil->nilai_keuangan,'#12b76a','keuangan'],'SDM'=>[$hasil->nilai_sdm,'#f79009','sdm']] as $lbl => [$val, $c, $key])
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $lbl }}</span>
                        <span class="font-bold" style="color:{{ $c }}">{{ number_format($val, 2) }}@if($key === $hasil->rekomendasi)<span class="ml-1 text-warning-500">★</span>@endif</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full" style="width:{{ $val }}%; background:{{ $c }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <span class="text-xs text-gray-400">Rekomendasi sistem: </span>
                <span class="font-bold text-sm" style="color:{{ $rc }}">{{ $hasil->label_rekomendasi }}</span>
            </div>
        @else
            <p class="text-xs text-gray-400 italic">
                @if(!$mahasiswa->sudah_tes_minat && !$mahasiswa->sudah_tes_bakat)
                    Belum mengerjakan kedua tes
                @elseif(!$mahasiswa->sudah_tes_minat)
                    Tes Minat belum selesai
                @else
                    Tes Bakat belum selesai
                @endif
            </p>
        @endif
    </div>

    {{-- 5. Aspek Tambahan --}}
    <div class="rounded-2xl border border-dashed border-warning-300 dark:border-warning-900 bg-warning-50/30 dark:bg-warning-900/10 p-5">
        <h3 class="font-bold text-warning-700 dark:text-warning-400 text-sm mb-1">5. Aspek Tambahan</h3>
        <p class="text-xs text-warning-600 dark:text-warning-500 mb-3">Dalam pengembangan</p>
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-gray-400">Aspek 1</span>
                <span class="text-gray-300 dark:text-gray-600">—</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 dark:text-gray-400">Aspek 2</span>
                <span class="text-gray-300 dark:text-gray-600">—</span>
            </div>
        </div>
    </div>

    {{-- 6. Penentuan Akhir (placeholder admin) --}}
    <div class="lg:col-span-3 rounded-2xl border border-brand-200 dark:border-brand-900 bg-brand-50/30 dark:bg-brand-900/10 p-5">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-brand-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Penentuan Konsentrasi Akhir</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Setelah seluruh aspek dipertimbangkan, admin akan menetapkan konsentrasi final mahasiswa pada modul berikutnya.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
