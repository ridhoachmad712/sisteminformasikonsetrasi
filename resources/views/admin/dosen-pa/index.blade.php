@extends('layouts.admin')
@section('title', 'Dosen PA')
@section('page-title', 'Dosen PA')

@section('content')
<div class="space-y-4">

    {{-- Flash --}}
    @if(session('success'))
    <div class="rounded-xl bg-success-50 dark:bg-success-500/10 border border-success-200 dark:border-success-500/20 px-4 py-3 text-sm text-success-700 dark:text-success-400">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl bg-error-50 dark:bg-error-500/10 border border-error-200 dark:border-error-500/20 px-4 py-3 text-sm text-error-700 dark:text-error-400">
        {{ session('error') }}
    </div>
    @endif

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Dosen Pembimbing Akademik</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola daftar dosen PA yang dapat dipilih mahasiswa</p>
            </div>
            <a href="{{ route('admin.dosen-pa.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-theme-xs transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Tambah Dosen
            </a>
        </div>

        {{-- Search --}}
        <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-800">
            <form method="GET" action="{{ route('admin.dosen-pa.index') }}">
                <div class="relative w-72">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIP..."
                        class="w-full h-9 pl-9 pr-4 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">NIP</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Mahasiswa</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    @forelse($dosenList as $dosen)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 dark:text-white">{{ $dosen->nama }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $dosen->nip ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 text-xs font-bold">
                                {{ $dosen->mahasiswa_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($dosen->aktif)
                            <span class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-500/20 px-2.5 py-1 text-xs font-medium text-success-700 dark:text-success-400">Aktif</span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-500 dark:text-gray-400">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.dosen-pa.edit', $dosen) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Edit
                                </a>
                                @if($dosen->mahasiswa_count === 0)
                                <form action="{{ route('admin.dosen-pa.destroy', $dosen) }}" method="POST"
                                    onsubmit="return confirm('Hapus dosen {{ $dosen->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-error-200 dark:border-error-500/30 px-3 py-1.5 text-xs font-medium text-error-600 dark:text-error-400 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                            @if($search)
                                Tidak ada dosen yang cocok dengan pencarian <span class="font-medium text-gray-600 dark:text-gray-300">"{{ $search }}"</span>
                            @else
                                Belum ada dosen PA. <a href="{{ route('admin.dosen-pa.create') }}" class="text-brand-500 hover:underline">Tambah sekarang</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($dosenList->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
            {{ $dosenList->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
