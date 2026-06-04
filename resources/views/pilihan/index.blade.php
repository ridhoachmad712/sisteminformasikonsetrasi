@extends('layouts.app')
@section('title', 'Pilihan Konsentrasi')

@section('content')
<div class="space-y-4"
    x-data="{
        p1: '{{ old('pilihan.0', $pilihan[0] ?? '') }}',
        p2: '{{ old('pilihan.1', $pilihan[1] ?? '') }}',
        p3: '{{ old('pilihan.2', $pilihan[2] ?? '') }}',
        showConfirm: false,
        labels: { pemasaran: 'Manajemen Pemasaran', keuangan: 'Manajemen Keuangan', sdm: 'Manajemen SDM' },
        get labelP1() { return this.labels[this.p1] || '—' },
        get labelP2() { return this.labels[this.p2] || '—' },
        get labelP3() { return this.labels[this.p3] || '—' },
    }">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-gray-400">
        <a href="{{ route('beranda') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Beranda</a>
        <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <span class="font-medium text-gray-700 dark:text-gray-300">Pilihan Konsentrasi</span>
    </div>

    {{-- Header --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h1 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M7 12h10M10 18h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Urutkan Pilihan Konsentrasi
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Tentukan preferensi Anda terhadap ketiga konsentrasi — Pilihan 1 paling diminati. Ini menjadi bahan pertimbangan, terpisah dari hasil tes.
        </p>
        @if($mahasiswa->sudah_pilih_konsentrasi)
        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-success-50 dark:bg-success-500/10 px-3 py-1 text-xs font-medium text-success-600 dark:text-success-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Sudah dipilih — dapat diperbarui kapan saja
        </div>
        @endif
    </div>

    <form action="{{ route('pilihan.store') }}" method="POST" class="space-y-3">
        @csrf

        @php
            $ranks = [
                ['no' => 1, 'model' => 'p1', 'name' => 'pilihan[0]', 'label' => 'Pilihan Pertama', 'desc' => 'Paling diminati', 'badge' => '#465fff'],
                ['no' => 2, 'model' => 'p2', 'name' => 'pilihan[1]', 'label' => 'Pilihan Kedua', 'desc' => 'Alternatif', 'badge' => '#12b76a'],
                ['no' => 3, 'model' => 'p3', 'name' => 'pilihan[2]', 'label' => 'Pilihan Ketiga', 'desc' => 'Cadangan', 'badge' => '#f79009'],
            ];
        @endphp

        @foreach($ranks as $r)
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl text-white font-bold shrink-0" style="background:{{ $r['badge'] }}">
                    {{ $r['no'] }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $r['label'] }}</p>
                    <p class="text-xs text-gray-400">{{ $r['desc'] }}</p>
                </div>
            </div>
            <select name="{{ $r['name'] }}" x-model="{{ $r['model'] }}" required
                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm font-medium text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                <option value="">— Pilih Konsentrasi —</option>
                @foreach($konsentrasi as $key => $label)
                <option value="{{ $key }}"
                    x-bind:disabled="
                        ('{{ $key }}' === p1 && '{{ $r['model'] }}' !== 'p1') ||
                        ('{{ $key }}' === p2 && '{{ $r['model'] }}' !== 'p2') ||
                        ('{{ $key }}' === p3 && '{{ $r['model'] }}' !== 'p3')
                    ">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @endforeach

        <div class="flex gap-3 pt-2 pb-2">
            <a href="{{ route('beranda') }}" class="h-12 px-5 flex items-center justify-center rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                Batal
            </a>
            <button type="button" @click="showConfirm = true"
                class="flex-1 h-12 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Simpan Pilihan
            </button>
        </div>
    </form>

    {{-- Modal Konfirmasi --}}
    <div x-show="showConfirm" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-6">
        <div class="bg-white dark:bg-gray-800 w-full max-w-sm rounded-2xl shadow-theme-lg p-6"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="scale-95 opacity-0"
            x-transition:enter-end="scale-100 opacity-100">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-1">Konfirmasi Pilihan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Pastikan urutan pilihan konsentrasi Anda sudah benar.</p>

            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-3 rounded-xl bg-gray-50 dark:bg-gray-900 px-4 py-3">
                    <span class="w-6 h-6 rounded-md flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#465fff">1</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="labelP1"></span>
                </div>
                <div class="flex items-center gap-3 rounded-xl bg-gray-50 dark:bg-gray-900 px-4 py-3">
                    <span class="w-6 h-6 rounded-md flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#12b76a">2</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="labelP2"></span>
                </div>
                <div class="flex items-center gap-3 rounded-xl bg-gray-50 dark:bg-gray-900 px-4 py-3">
                    <span class="w-6 h-6 rounded-md flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#f79009">3</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="labelP3"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="button" @click="showConfirm = false"
                    class="h-11 rounded-xl border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    Ubah
                </button>
                <button type="button" @click="showConfirm = false; $nextTick(() => document.querySelector('form').submit())"
                    class="h-11 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold transition-colors">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
