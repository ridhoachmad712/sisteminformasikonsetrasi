@extends('layouts.admin')
@section('title', 'Tambah Admin')
@section('page-title', 'Tambah User Admin')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali ke Daftar
    </a>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Tambah User Admin Baru</h2>
        <p class="text-sm text-gray-400 mb-6">Akun ini akan memiliki akses penuh ke panel administrator.</p>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap <span class="text-error-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama admin" required
                    class="@error('name') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                @error('name')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email <span class="text-error-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@email.com" required
                    class="@error('email') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                @error('email')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password <span class="text-error-500">*</span></label>
                <div x-data="{show:false}" class="relative">
                    <input :type="show?'text':'password'" name="password" required minlength="8" placeholder="Minimal 8 karakter"
                        class="@error('password') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                    <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                        <svg x-show="show" class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    </button>
                </div>
                @error('password')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password <span class="text-error-500">*</span></label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
