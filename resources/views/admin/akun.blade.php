@extends('layouts.admin')
@section('title', 'Akun Admin')
@section('page-title', 'Akun Admin')

@section('content')
<div class="max-w-xl space-y-5">

    {{-- Profil --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 3.5C7.306 3.5 3.5 7.306 3.5 12c0 2.153.8 4.118 2.119 5.616.553-2.306 2.629-4.021 5.105-4.021h2.55c2.476 0 4.552 1.715 5.105 4.021C19.699 16.118 20.5 14.153 20.5 12c0-4.694-3.806-8.5-8.5-8.5z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.5"/></svg>
            Profil Administrator
        </h2>
        <p class="text-sm text-gray-400 mb-5">Perbarui nama dan email akun admin.</p>

        <form action="{{ route('admin.akun.profil') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
            </div>
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Simpan Profil
            </button>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Ganti Password
        </h2>
        <p class="text-sm text-gray-400 mb-5">Minimal 8 karakter. Gunakan kombinasi huruf & angka.</p>

        <form action="{{ route('admin.akun.password') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Lama</label>
                <div x-data="{show:false}" class="relative">
                    <input :type="show?'text':'password'" name="password_lama" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent py-2.5 pr-11 pl-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                    <button type="button" @click="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                        <svg x-show="show" class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                <input type="password" name="password_baru" required minlength="8" placeholder="Minimal 8 karakter"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password Baru</label>
                <input type="password" name="password_baru_confirmation" required placeholder="Ulangi password baru"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">
            </div>
            <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Ubah Password
            </button>
        </form>
    </div>

</div>
@endsection