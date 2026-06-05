@extends('layouts.admin')
@section('title', 'Input Prestasi Relevan')
@section('page-title', 'Input Prestasi Relevan')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.rekap.show', $mahasiswa) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali ke Rekap
    </a>

    {{-- Identitas --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 mb-4 flex items-center gap-4">
        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-brand-500 text-white text-lg font-bold shrink-0">
            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
        </div>
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">{{ $mahasiswa->nama }}</h2>
            <p class="text-sm text-gray-400">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Input Prestasi Relevan</h3>
        <p class="text-sm text-gray-400 mb-4">
            Berikan skor <strong>0–15</strong> untuk masing-masing konsentrasi sesuai relevansi prestasi mahasiswa
            (sertifikat, lomba, organisasi, pelatihan). Komponen ini berkontribusi <strong>15%</strong> di skor final.
        </p>

        {{-- Panduan skor --}}
        <div class="rounded-xl border border-brand-100 dark:border-brand-900 bg-brand-50/50 dark:bg-brand-900/10 px-4 py-3 mb-5 text-xs text-brand-700 dark:text-brand-300">
            <p class="font-semibold mb-1">Panduan skor:</p>
            <ul class="space-y-0.5 list-disc list-inside">
                <li><strong>0</strong> — tidak ada prestasi terkait</li>
                <li><strong>1–5</strong> — prestasi level internal kampus/kelas</li>
                <li><strong>6–10</strong> — prestasi level fakultas/regional</li>
                <li><strong>11–15</strong> — prestasi level nasional/internasional</li>
            </ul>
        </div>

        <form action="{{ route('admin.prestasi.update', $mahasiswa) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            @php
                $kons = [
                    ['key' => 'pemasaran', 'label' => 'Manajemen Pemasaran', 'warna' => '#465fff'],
                    ['key' => 'keuangan',  'label' => 'Manajemen Keuangan',  'warna' => '#12b76a'],
                    ['key' => 'sdm',       'label' => 'Manajemen SDM',       'warna' => '#f79009'],
                ];
            @endphp

            @foreach($kons as $k)
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 p-4 flex items-center gap-4">
                <div class="flex items-center gap-2 flex-1">
                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:{{ $k['warna'] }}"></span>
                    <label for="prestasi_{{ $k['key'] }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $k['label'] }}</label>
                </div>
                <input type="number" name="prestasi[{{ $k['key'] }}]" id="prestasi_{{ $k['key'] }}"
                    value="{{ old('prestasi.'.$k['key'], $prestasi[$k['key']] ?? 0) }}"
                    min="0" max="15" step="1" required
                    class="w-20 h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm font-semibold text-center text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                <span class="text-xs text-gray-400">/ 15</span>
                @error('prestasi.'.$k['key'])<p class="text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            @endforeach

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan (opsional)</label>
                <textarea name="catatan" rows="3" placeholder="Contoh: Juara 2 Marketing Competition Nasional 2024, anggota HMJ Manajemen, dll."
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-3 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">{{ old('catatan', $mahasiswa->catatan_prestasi) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.rekap.show', $mahasiswa) }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
