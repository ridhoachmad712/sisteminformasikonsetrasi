@extends('layouts.app')
@section('title', 'Nilai Mata Kuliah')

@section('content')
<div class="space-y-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-gray-400">
        <a href="{{ route('beranda') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Beranda</a>
        <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <span class="font-medium text-gray-700 dark:text-gray-300">Nilai Mata Kuliah</span>
    </div>

    {{-- Header --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h1 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.42a12 12 0 01.84 4.42 12 12 0 01-7 .91 12 12 0 01-7-.91 12 12 0 01.84-4.42L12 14z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Input Nilai Mata Kuliah
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Masukkan nilai akhir (huruf) untuk 9 mata kuliah berikut. Nilai ini menjadi data pendukung rekomendasi konsentrasi Anda.
        </p>
        @if($mahasiswa->sudah_input_nilai)
        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-success-50 dark:bg-success-500/10 px-3 py-1 text-xs font-medium text-success-600 dark:text-success-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Sudah diisi — Anda dapat memperbarui kapan saja
        </div>
        @endif
    </div>

    <form action="{{ route('nilai.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @foreach($mataKuliah as $key => $namaMk)
                <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                    <label for="nilai_{{ $key }}" class="text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <span class="text-gray-400 mr-1.5">{{ $loop->iteration }}.</span>{{ $namaMk }}
                    </label>
                    <select name="nilai[{{ $key }}]" id="nilai_{{ $key }}" required
                        class="shrink-0 w-24 h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm font-semibold text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                        <option value="">—</option>
                        @foreach($pilihan as $p)
                        <option value="{{ $p }}" @selected(old("nilai.$key", $nilai[$key] ?? '') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Skala nilai --}}
        <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40 px-4 py-3">
            <p class="text-xs text-gray-400 leading-relaxed">
                <span class="font-medium text-gray-500 dark:text-gray-300">Bobot nilai:</span>
                A (96) · A- (88) · B+ (83) · B (78) · B- (73) · C+ (68) · C (63) · C- (58) · D (48) · E (21)
            </p>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3 pb-2">
            <a href="{{ route('beranda') }}" class="h-12 px-5 flex items-center justify-center rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="flex-1 h-12 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Simpan Nilai
            </button>
        </div>
    </form>

</div>
@endsection
