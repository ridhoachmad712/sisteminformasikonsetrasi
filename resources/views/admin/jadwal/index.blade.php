@extends('layouts.admin')
@section('title', 'Jadwal Tes')
@section('page-title', 'Jadwal Tes')

@section('content')

{{-- Status Aktif Sekarang --}}
@php
$angkatanSample = $angkatanList->first();
$jadwalAktif = \App\Models\JadwalTes::where('aktif', true)
    ->where('tanggal_mulai', '<=', now())
    ->where('tanggal_selesai', '>=', now())
    ->get();
@endphp

@if($jadwalAktif->isNotEmpty())
<div class="mb-5 rounded-2xl border border-success-200 dark:border-success-900 bg-success-50 dark:bg-success-900/20 p-4">
    <div class="flex items-center gap-3">
        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-success-100 dark:bg-success-500/20 shrink-0">
            <svg class="w-5 h-5 text-success-600 dark:text-success-400" viewBox="0 0 24 24" fill="none">
                <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-success-700 dark:text-success-400">Tes sedang berlangsung</p>
            <p class="text-xs text-success-600 dark:text-success-500 mt-0.5">
                {{ $jadwalAktif->count() }} jadwal aktif saat ini. Mahasiswa dapat mengakses tes.
            </p>
        </div>
    </div>
</div>
@else
<div class="mb-5 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/40 p-4">
    <div class="flex items-center gap-3">
        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 shrink-0">
            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tidak ada tes yang sedang berlangsung</p>
            <p class="text-xs text-gray-400 mt-0.5">Mahasiswa tidak dapat mengakses halaman tes saat ini.</p>
        </div>
    </div>
</div>
@endif

<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">Daftar Jadwal Tes</h2>
            <p class="text-xs text-gray-400 mt-0.5">Atur kapan mahasiswa bisa mengakses tes konsentrasi</p>
        </div>
        <a href="{{ route('admin.jadwal.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-theme-xs transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Buat Jadwal
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Nama Jadwal</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Jenis Tes</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Angkatan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Mulai</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Selesai</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @forelse($jadwal as $j)
                @php
                    $statusConfig = match($j->status) {
                        'berlangsung'    => ['bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400', 'Berlangsung'],
                        'belum_mulai'    => ['bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400', 'Belum Mulai'],
                        'selesai'        => ['bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400', 'Selesai'],
                        'nonaktif'       => ['bg-error-50 dark:bg-error-500/10 text-error-500 dark:text-error-400', 'Nonaktif'],
                        default          => ['bg-gray-100 text-gray-500', $j->status],
                    };
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $j->nama }}</p>
                        @if($j->keterangan)
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $j->keterangan }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-center">
                        @php
                            $jcfg = match($j->jenis_tes) {
                                'minat' => ['#465fff', 'Minat'],
                                'bakat' => ['#12b76a', 'Bakat'],
                                default => ['#6b7280', 'Keduanya'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                            style="background:{{ $jcfg[0] }}18; color:{{ $jcfg[0] }}">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $jcfg[0] }}"></span>
                            {{ $jcfg[1] }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if($j->angkatan)
                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-600 dark:text-gray-400">
                                {{ $j->angkatan }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-brand-50 dark:bg-brand-500/10 px-2.5 py-1 text-xs font-medium text-brand-600 dark:text-brand-400">
                                Semua
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-gray-700 dark:text-gray-300 text-xs font-medium">{{ $j->tanggal_mulai->format('d M Y') }}</p>
                        <p class="text-gray-400 text-xs">{{ $j->tanggal_mulai->format('H:i') }} WITA</p>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-gray-700 dark:text-gray-300 text-xs font-medium">{{ $j->tanggal_selesai->format('d M Y') }}</p>
                        <p class="text-gray-400 text-xs">{{ $j->tanggal_selesai->format('H:i') }} WITA</p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusConfig[0] }}">
                            @if($j->status === 'berlangsung')
                            <span class="w-1.5 h-1.5 rounded-full bg-success-500 mr-1.5 animate-pulse"></span>
                            @endif
                            {{ $statusConfig[1] }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            {{-- Toggle aktif --}}
                            <form action="{{ route('admin.jadwal.toggle', $j) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="{{ $j->aktif ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    class="flex items-center justify-center w-8 h-8 rounded-lg border transition-colors
                                    {{ $j->aktif
                                        ? 'border-success-200 dark:border-success-900 text-success-500 hover:bg-success-50 dark:hover:bg-success-500/10'
                                        : 'border-gray-200 dark:border-gray-800 text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5' }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                        @if($j->aktif)
                                        <path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                        @else
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/>
                                        @endif
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('admin.jadwal.edit', $j) }}"
                                class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 rounded-lg border border-error-200 dark:border-error-900 text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m3 0v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6h16z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Belum ada jadwal tes</p>
                                <p class="text-xs text-gray-400 mt-1">Buat jadwal tes agar mahasiswa bisa mengakses tes.</p>
                            </div>
                            <a href="{{ route('admin.jadwal.create') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition-colors mt-1">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                Buat Jadwal Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jadwal->hasPages())
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-xs text-gray-400">{{ $jadwal->total() }} jadwal</p>
        {{ $jadwal->links() }}
    </div>
    @endif
</div>

{{-- Penjelasan cara kerja --}}
<div class="mt-4 rounded-2xl border border-brand-200 dark:border-brand-900 bg-brand-50 dark:bg-brand-900/20 p-5">
    <h3 class="text-sm font-semibold text-brand-700 dark:text-brand-400 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
        Cara Kerja Jadwal
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-brand-600 dark:text-brand-400">
        @foreach([
            ['Spesifik Angkatan', 'Jika angkatan diisi, hanya mahasiswa angkatan tersebut yang terikat jadwal ini.'],
            ['Global (Semua)', 'Jika angkatan dikosongkan, jadwal berlaku untuk semua mahasiswa.'],
            ['Prioritas', 'Jadwal spesifik angkatan lebih diprioritaskan daripada jadwal global.'],
        ] as [$title, $desc])
        <div class="rounded-xl bg-white dark:bg-brand-950/30 border border-brand-200 dark:border-brand-800 p-3">
            <p class="font-semibold mb-1">{{ $title }}</p>
            <p class="leading-relaxed text-brand-500 dark:text-brand-500">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
