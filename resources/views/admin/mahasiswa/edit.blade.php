@extends('layouts.admin')
@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Mahasiswa')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali
    </a>
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Edit Data Mahasiswa</h2>
        <p class="text-sm text-gray-400 mb-6">Perbarui informasi mahasiswa.</p>
        <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NIM <span class="text-error-500">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                    @error('nim')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap <span class="text-error-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Angkatan</label>
                    <input type="text" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}" maxlength="4"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" minlength="6"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900 dark:text-white">
                    <p class="mt-1 text-xs text-gray-400">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah.</p>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Update
                </button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
