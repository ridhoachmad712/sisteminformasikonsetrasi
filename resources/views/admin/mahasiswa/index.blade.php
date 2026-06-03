@extends('layouts.admin')
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')

@section('content')
<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">Daftar Mahasiswa</h2>
            <p class="text-xs text-gray-400 mt-0.5">Kelola data mahasiswa peserta tes</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.mahasiswa.import.form') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-brand-300 dark:border-brand-800 bg-brand-50 dark:bg-brand-500/10 px-4 py-2.5 text-sm font-semibold text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-brand-500/20 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Import CSV
            </a>
            <a href="{{ route('admin.mahasiswa.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-theme-xs transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Tambah
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04 9.37C3.04 5.88 5.88 3.04 9.38 3.04c3.5 0 6.33 2.84 6.33 6.33 0 3.5-2.83 6.34-6.33 6.34C5.87 15.71 3.04 12.87 3.04 9.37zm6.33-7.83C5.05 1.54 1.54 5.05 1.54 9.37c0 4.32 3.51 7.83 7.83 7.83 1.9 0 3.63-.67 4.98-1.78l2.82 2.82a.75.75 0 001.06-1.06l-2.82-2.82A7.78 7.78 0 0017.21 9.37c0-4.32-3.51-7.83-7.84-7.83z" fill="currentColor"/></svg>
                <input type="text" name="search" placeholder="Cari NIM atau nama..." value="{{ request('search') }}"
                    class="w-full h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent pl-9 pr-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-white/30 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
            </div>
            <select name="angkatan"
                class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm text-gray-700 dark:text-gray-300 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                <option value="">Semua Angkatan</option>
                @foreach($angkatanList as $a)
                <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                @endforeach
            </select>
            <button type="submit" class="h-10 px-4 rounded-lg bg-brand-500 text-white text-sm font-medium hover:bg-brand-600 transition-colors">Cari</button>
            @if(request('search') || request('angkatan'))
            <a href="{{ route('admin.mahasiswa.index') }}" class="h-10 px-4 flex items-center rounded-lg border border-gray-300 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Mahasiswa</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Angkatan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden md:table-cell">Email</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Status Tes</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @forelse($mahasiswa as $m)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-9 h-9 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 text-sm font-bold shrink-0">
                                {{ strtoupper(substr($m->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $m->nama }}</p>
                                <p class="text-xs text-gray-400">{{ $m->nim }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center text-gray-600 dark:text-gray-400">{{ $m->angkatan }}</td>
                    <td class="px-4 py-4 text-gray-500 dark:text-gray-400 hidden md:table-cell">{{ $m->email ?? '—' }}</td>
                    <td class="px-4 py-4 text-center">
                        @if($m->sudah_tes)
                        <span class="inline-flex items-center gap-1 rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-1 text-xs font-medium text-success-600 dark:text-success-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                            Sudah Tes
                        </span>
                        @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                            Belum Tes
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.mahasiswa.edit', $m) }}"
                                class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors" title="Edit">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            @if($m->sudah_tes)
                            <form action="{{ route('admin.mahasiswa.reset-tes', $m) }}" method="POST" class="inline" onsubmit="return confirm('Reset status tes?')">
                                @csrf
                                <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg border border-warning-200 dark:border-warning-900 text-warning-500 hover:bg-warning-50 dark:hover:bg-warning-500/10 transition-colors" title="Reset Tes">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M1 4v6h6M23 20v-6h-6M20.49 9A9 9 0 005.64 5.64L1 10M23 14l-4.64 4.36A9 9 0 013.51 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('admin.mahasiswa.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Hapus mahasiswa ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg border border-error-200 dark:border-error-900 text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m3 0v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6h16z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-700" viewBox="0 0 24 24" fill="none"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <p class="text-sm text-gray-400">Tidak ada data mahasiswa ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-xs text-gray-400">{{ $mahasiswa->firstItem() }}–{{ $mahasiswa->lastItem() }} dari {{ $mahasiswa->total() }} mahasiswa</p>
        {{ $mahasiswa->withQueryString()->links() }}
    </div>
</div>
@endsection
