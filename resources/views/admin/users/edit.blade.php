@extends('layouts.admin')
@section('title', 'Edit Admin')
@section('page-title', 'Edit User Admin')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali
    </a>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Edit User Admin</h2>
        <p class="text-sm text-gray-400 mb-6">Perbarui data admin. Password opsional — kosongkan jika tidak ingin mengubah.</p>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap <span class="text-error-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                @error('name')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email <span class="text-error-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                @error('email')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Ubah Password (opsional)</p>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                        <input type="password" name="password" minlength="8" placeholder="Kosongkan jika tidak diubah"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                        @error('password')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Update
                </button>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
