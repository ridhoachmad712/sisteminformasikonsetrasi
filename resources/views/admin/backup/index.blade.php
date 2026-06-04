@extends('layouts.admin')
@section('title', 'Backup Database')
@section('page-title', 'Backup Database')

@section('content')
<div class="max-w-2xl space-y-5">

    {{-- Flash --}}
    @if(session('success'))
    <div class="rounded-xl bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-500/20 px-4 py-3 text-sm text-success-700 dark:text-success-400 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl bg-error-50 dark:bg-error-500/10 border border-error-200 dark:border-error-500/20 px-4 py-3 text-sm text-error-700 dark:text-error-400 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 012 0v3a1 1 0 11-2 0V9zm1 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Download --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-500/10 shrink-0">
                <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Download Backup</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Unduh seluruh isi database sebagai file <code class="text-xs bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">.sql</code>.
                    Simpan file ini di tempat yang aman.
                </p>
            </div>
        </div>
        <a href="{{ route('admin.backup.download') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Download Database
        </a>
    </div>

    {{-- Upload / Restore --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-warning-50 dark:bg-warning-500/10 shrink-0">
                <svg class="w-5 h-5 text-warning-500" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Restore Database</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Upload file <code class="text-xs bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">.sql</code> untuk memulihkan database.
                </p>
            </div>
        </div>

        {{-- Peringatan --}}
        <div class="mb-5 rounded-xl border border-error-200 dark:border-error-500/30 bg-error-50 dark:bg-error-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-4 h-4 text-error-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <p class="text-xs text-error-700 dark:text-error-400 leading-relaxed">
                <strong>Peringatan:</strong> Proses restore akan <strong>menimpa seluruh data yang ada</strong>.
                Pastikan Anda sudah download backup terbaru sebelum melanjutkan.
            </p>
        </div>

        <form action="{{ route('admin.backup.upload') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return confirm('PERHATIAN: Seluruh data saat ini akan ditimpa. Lanjutkan restore database?')">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Pilih File SQL <span class="text-error-500">*</span>
                </label>
                <input type="file" name="sql_file" accept=".sql,.txt" required
                    class="block w-full text-sm text-gray-700 dark:text-gray-300
                        file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                        file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-600
                        dark:file:bg-brand-500/10 dark:file:text-brand-400
                        hover:file:bg-brand-100 dark:hover:file:bg-brand-500/20
                        cursor-pointer">
                @error('sql_file')<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-warning-500 hover:bg-warning-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Restore Database
            </button>
        </form>
    </div>

</div>
@endsection
