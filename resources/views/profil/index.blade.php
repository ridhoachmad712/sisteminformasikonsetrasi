@extends('layouts.app')
@section('title', 'Profil Saya')

@push('styles')
<style>
@keyframes barFill { from { width: 0 } }
.bar-animate { animation: barFill 1s ease-out forwards; }
</style>
@endpush

@section('content')
<div class="space-y-4">

    {{-- Header --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 sm:p-6">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-brand-500 text-white text-xl font-bold shrink-0">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $mahasiswa->nama }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">NIM: {{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</p>
                @if($mahasiswa->email)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $mahasiswa->email }}</p>
                @endif
            </div>
            <div class="ml-auto">
                @if($mahasiswa->sudah_tes)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-success-50 dark:bg-success-500/10 px-3 py-1.5 text-xs font-medium text-success-600 dark:text-success-400">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                    Sudah Tes
                </span>
                @else
                <a href="{{ route('tes.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition-colors">
                    Mulai Tes
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Hasil Tes (jika sudah tes) --}}
    @if($hasil)
    @php $rc = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$hasil->rekomendasi] ?? '#6b7280'; @endphp
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Hasil Tes Konsentrasi
        </h2>
        <div class="flex items-center gap-4 mb-5">
            <div class="inline-flex items-center rounded-2xl px-4 py-2.5" style="background:{{ $rc }}15; border:1.5px solid {{ $rc }}40">
                <span class="font-bold" style="color:{{ $rc }}">{{ $hasil->label_rekomendasi }}</span>
            </div>
            <span class="text-xs text-gray-400">{{ $hasil->created_at->format('d F Y') }}</span>
        </div>
        <div class="space-y-3">
            @foreach(['Pemasaran'=>[$hasil->nilai_pemasaran,'#465fff'],'Keuangan'=>[$hasil->nilai_keuangan,'#12b76a'],'SDM'=>[$hasil->nilai_sdm,'#f79009']] as $lbl=>[$val,$c])
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-600 dark:text-gray-400">{{ $lbl }}</span>
                    <span class="font-bold" style="color:{{ $c }}">{{ number_format($val,2) }}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full bar-animate" style="width:{{ $val }}%; background:{{ $c }}; animation-delay:{{ $loop->index * 150 }}ms"></div>
                </div>
            </div>
            @endforeach
        </div>
        <a href="{{ route('tes.hasil') }}" class="mt-4 inline-flex items-center gap-1.5 text-sm text-brand-500 hover:text-brand-600 font-medium">
            Lihat Detail Hasil
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    @endif


</div>
@endsection
