@extends('layouts.admin')
@section('title', 'Tambah Mahasiswa')
@section('page-title', 'Tambah Mahasiswa')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali ke Daftar
    </a>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Tambah Mahasiswa Baru</h2>
        <p class="text-sm text-gray-400 mb-6">Isi form berikut untuk mendaftarkan mahasiswa baru.</p>

        <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">NIM <span class="text-error-500">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 2023001" required
                        class="@error('nim') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                    @error('nim')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap <span class="text-error-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap" required
                        class="@error('nama') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                    @error('nama')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Angkatan <span class="text-error-500">*</span></label>
                    <input type="text" name="angkatan" value="{{ old('angkatan') }}" placeholder="2023" maxlength="4" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                    @error('angkatan')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password <span class="text-error-500">*</span></label>
                    <div x-data="{show:false}" class="relative">
                        <input :type="show?'text':'password'" name="password" placeholder="Minimal 6 karakter" required minlength="6"
                            class="@error('password') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                        <span @click="show=!show" class="absolute top-1/2 right-4 -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 13.862C7.234 13.862 4.868 12.137 3.923 9.702c.945-2.435 3.31-4.16 6.077-4.16 2.767 0 5.131 1.725 6.077 4.16C15.132 12.137 12.766 13.862 10 13.862zm0-9.82C6.482 4.043 3.495 6.31 2.416 9.459c-.054.158-.054.329 0 .487C3.495 13.096 6.482 15.362 10 15.362c3.518 0 6.505-2.266 7.585-5.416.053-.158.053-.329 0-.487C16.505 6.31 13.518 4.043 10 4.043z" fill="#98A2B3"/></svg>
                        </span>
                    </div>
                    @error('password')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 sm:flex-none bg-brand-500 hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="7,3 7,8 15,8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
