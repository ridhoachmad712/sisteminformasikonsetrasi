@extends('layouts.admin')
@section('title', 'Tambah Dosen PA')
@section('page-title', 'Tambah Dosen PA')

@section('content')
<div class="max-w-lg">

    <div class="mb-4 flex items-center gap-1.5 text-xs text-gray-400">
        <a href="{{ route('admin.dosen-pa.index') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Dosen PA</a>
        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <span class="text-gray-700 dark:text-gray-300 font-medium">Tambah</span>
    </div>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-5">Data Dosen PA</h2>

        <form action="{{ route('admin.dosen-pa.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Nama Lengkap <span class="text-error-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Dr. Ahmad Fauzi, M.M."
                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900 @error('nama') border-error-300 @enderror">
                @error('nama')<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            {{-- NIP --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    NIP <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 197501012005011001"
                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900 @error('nip') border-error-300 @enderror">
                @error('nip')<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            {{-- Status --}}
            <div class="flex items-center justify-between rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Aktif</p>
                    <p class="text-xs text-gray-400 mt-0.5">Dosen nonaktif tidak muncul di pilihan mahasiswa</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" name="aktif" value="1" class="sr-only peer" {{ old('aktif', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 dark:peer-focus:ring-brand-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-brand-500"></div>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.dosen-pa.index') }}"
                    class="h-11 px-5 flex items-center justify-center rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 h-11 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
