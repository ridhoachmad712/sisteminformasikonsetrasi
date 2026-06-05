@extends('layouts.admin')
@section('title', 'Import Prestasi')
@section('page-title', 'Import Prestasi Mahasiswa')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.rekap.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali ke Rekap
    </a>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 mb-4">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Import Prestasi Massal</h2>
        <p class="text-sm text-gray-400 mb-5">Upload file Excel atau CSV untuk update skor prestasi banyak mahasiswa sekaligus.</p>

        {{-- Info format --}}
        <div class="rounded-xl border border-brand-200 dark:border-brand-900 bg-brand-50 dark:bg-brand-900/20 p-4 mb-5">
            <h3 class="text-sm font-semibold text-brand-700 dark:text-brand-400 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
                Format Kolom
            </h3>
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-brand-200 dark:border-brand-800">
                        <th class="pb-2 text-left text-brand-600 dark:text-brand-400 font-semibold">Kolom</th>
                        <th class="pb-2 text-left text-brand-600 dark:text-brand-400 font-semibold">Isi</th>
                        <th class="pb-2 text-left text-brand-600 dark:text-brand-400 font-semibold">Wajib?</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-100 dark:divide-brand-900/50">
                    @foreach([
                        ['nim', 'Nomor Induk Mahasiswa (12 digit)', 'Ya'],
                        ['pemasaran', 'Skor 0–15 untuk Manajemen Pemasaran', 'Salah satu wajib'],
                        ['keuangan', 'Skor 0–15 untuk Manajemen Keuangan', 'Salah satu wajib'],
                        ['sdm', 'Skor 0–15 untuk Manajemen SDM', 'Salah satu wajib'],
                        ['catatan', 'Keterangan prestasi (opsional)', 'Tidak'],
                    ] as [$k,$d,$w])
                    <tr>
                        <td class="py-1.5 font-mono text-brand-700 dark:text-brand-300">{{ $k }}</td>
                        <td class="py-1.5 text-brand-600 dark:text-brand-400">{{ $d }}</td>
                        <td class="py-1.5">
                            <span class="@if($w==='Ya') text-error-500 font-semibold @elseif($w==='Tidak') text-gray-400 @else text-warning-600 @endif">{{ $w }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 pt-3 border-t border-brand-200 dark:border-brand-800">
                <a href="{{ route('admin.prestasi.import.template') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-brand-600 dark:text-brand-400 hover:underline">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download Template CSV
                </a>
            </div>
        </div>

        {{-- Panduan skor --}}
        <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40 px-4 py-3 mb-5 text-xs text-gray-600 dark:text-gray-400">
            <p class="font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Panduan Skor (0–15):</p>
            <ul class="space-y-0.5 list-disc list-inside">
                <li><strong>0</strong> — tidak ada prestasi terkait</li>
                <li><strong>1–5</strong> — prestasi level internal kampus/kelas</li>
                <li><strong>6–10</strong> — prestasi level fakultas/regional</li>
                <li><strong>11–15</strong> — prestasi level nasional/internasional</li>
            </ul>
            <p class="mt-2 italic">Nilai > 15 akan otomatis dipotong ke 15. Nilai < 0 dianggap 0.</p>
        </div>

        <form action="{{ route('admin.prestasi.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div x-data="{fileName: ''}" class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    File <span class="text-error-500">*</span>
                </label>
                <div class="relative flex items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-brand-400 dark:hover:border-brand-600 transition-colors cursor-pointer bg-gray-50 dark:bg-gray-800/30"
                    @click="$refs.fileInput.click()">
                    <input type="file" name="file" accept=".csv,.xlsx,.xls" required x-ref="fileInput" class="hidden"
                        @change="fileName = $event.target.files[0]?.name || ''">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <p x-show="!fileName" class="text-sm text-gray-500 dark:text-gray-400">Klik untuk memilih file</p>
                        <p x-show="fileName" class="text-sm font-medium text-brand-600 dark:text-brand-400" x-text="fileName"></p>
                        <p class="text-xs text-gray-400 mt-1">.xlsx, .xls, atau .csv · Maks 5MB</p>
                    </div>
                </div>
                @error('file')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Upload &amp; Import
                </button>
                <a href="{{ route('admin.rekap.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
