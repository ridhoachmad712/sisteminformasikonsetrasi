@extends('layouts.admin')
@section('title', 'Pemeringkatan Konsentrasi')
@section('page-title', 'Pemeringkatan Konsentrasi')

@php
$warna = ['pemasaran' => '#465fff', 'keuangan' => '#12b76a', 'sdm' => '#f79009'];
$label = ['pemasaran' => 'Pemasaran', 'keuangan' => 'Keuangan', 'sdm' => 'SDM'];
@endphp

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
        <div class="text-xs text-gray-400 mb-1">Total Diranking</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stat['total'] }}</div>
    </div>
    @foreach(['pemasaran', 'keuangan', 'sdm'] as $k)
    <div class="rounded-2xl border p-4" style="border-color:{{ $warna[$k] }}30; background:{{ $warna[$k] }}08">
        <div class="flex items-center gap-1.5 text-xs mb-1" style="color:{{ $warna[$k] }}">
            <span class="w-2 h-2 rounded-full" style="background:{{ $warna[$k] }}"></span>
            <span>{{ $label[$k] }}</span>
        </div>
        <div class="text-2xl font-bold" style="color:{{ $warna[$k] }}">{{ $stat[$k] }}</div>
    </div>
    @endforeach
</div>

<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between gap-3 mb-3 flex-wrap">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Peringkat Konsentrasi Mahasiswa</h2>
                <p class="text-xs text-gray-400 mt-0.5">Urutan 1-2-3 skor akhir per mahasiswa</p>
            </div>
            <a href="{{ route('admin.peringkat.export', request()->query()) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-success-300 dark:border-success-800 bg-success-50 dark:bg-success-500/10 px-4 py-2.5 text-sm font-semibold text-success-600 dark:text-success-400 hover:bg-success-100 dark:hover:bg-success-500/20 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Export CSV
            </a>
        </div>
        {{-- Filter --}}
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative flex-1 min-w-[180px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04 9.37C3.04 5.88 5.88 3.04 9.38 3.04c3.5 0 6.33 2.84 6.33 6.33 0 3.5-2.83 6.34-6.33 6.34C5.87 15.71 3.04 12.87 3.04 9.37z" fill="currentColor"/></svg>
                <input type="text" name="search" placeholder="Cari NIM atau nama..." value="{{ request('search') }}"
                    class="w-full h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent pl-9 pr-4 text-sm dark:bg-gray-900 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none">
            </div>
            <select name="angkatan" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300">
                <option value="">Semua Angkatan</option>
                @foreach($angkatanList as $a)
                <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                @endforeach
            </select>
            <select name="konsentrasi" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300">
                <option value="">Semua Rekomendasi</option>
                <option value="pemasaran" @selected(request('konsentrasi')=='pemasaran')>Pemasaran</option>
                <option value="keuangan" @selected(request('konsentrasi')=='keuangan')>Keuangan</option>
                <option value="sdm" @selected(request('konsentrasi')=='sdm')>SDM</option>
            </select>
            <select name="sort" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300">
                <option value="">Urut: Nama</option>
                <option value="skor" @selected(request('sort')=='skor')>Urut: Skor Tertinggi</option>
            </select>
            <button class="h-10 px-4 rounded-lg bg-brand-500 text-white text-sm font-medium hover:bg-brand-600">Cari</button>
            @if(request()->hasAny(['search','angkatan','konsentrasi','sort']))
            <a href="{{ route('admin.peringkat.index') }}" class="h-10 px-3 flex items-center rounded-lg border border-gray-300 dark:border-gray-700 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3 text-center w-12">#</th>
                    <th class="px-4 py-3 text-left">Mahasiswa</th>
                    <th class="px-4 py-3 text-center">Hasil Konsentrasi</th>
                    <th class="px-4 py-3 text-center">Peringkat 1</th>
                    <th class="px-4 py-3 text-center">Peringkat 2</th>
                    <th class="px-4 py-3 text-center">Peringkat 3</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @php $no = ($rows->currentPage() - 1) * $rows->perPage(); @endphp
                @forelse($rows as $r)
                @php
                    $m  = $r['mahasiswa'];
                    $rc = $warna[$r['rekomendasi']];
                    $no++;
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    {{-- No --}}
                    <td class="px-4 py-4 text-center text-xs text-gray-400 font-medium">{{ $no }}</td>

                    {{-- Mahasiswa --}}
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-9 h-9 rounded-full text-white text-xs font-bold shrink-0" style="background:{{ $rc }}">
                                {{ strtoupper(substr($m->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $m->nama }}</p>
                                <p class="text-xs text-gray-400">{{ $m->nim }} · {{ $m->angkatan }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Hasil Konsentrasi (rekomendasi) --}}
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold"
                            style="background:{{ $rc }}20; color:{{ $rc }}">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ $label[$r['rekomendasi']] }}
                        </span>
                    </td>

                    {{-- 3 Peringkat --}}
                    @foreach(['rank1','rank2','rank3'] as $rk)
                    @php $cell = $r[$rk]; $cellColor = $warna[$cell['k']]; @endphp
                    <td class="px-4 py-4 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-bold tabular-nums" style="color:{{ $cellColor }}">{{ number_format($cell['v'], 2) }}</span>
                            <span class="text-[10px] uppercase font-medium tracking-wide" style="color:{{ $cellColor }}">{{ $label[$cell['k']] }}</span>
                        </div>
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-sm text-gray-400">
                        <div class="inline-flex flex-col items-center gap-2">
                            <svg class="w-10 h-10 text-gray-300 dark:text-gray-700" viewBox="0 0 24 24" fill="none"><path d="M7 4h10v3a5 5 0 01-10 0V4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Belum ada data peringkat.</span>
                            <span class="text-xs">Mahasiswa perlu menyelesaikan tes & input data dulu.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-xs text-gray-400">{{ $rows->total() }} mahasiswa</p>
        {{ $rows->withQueryString()->links() }}
    </div>
</div>

{{-- Info --}}
<div class="mt-4 rounded-xl border border-brand-200 dark:border-brand-900 bg-brand-50/30 dark:bg-brand-900/10 px-4 py-3 flex items-start gap-2.5">
    <svg class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
    <p class="text-xs text-brand-700 dark:text-brand-400">
        Skor akhir dihitung dari: <strong>MINAT 40% · Nilai MK 25% · Tes Minat&amp;Bakat 15% · Prestasi 15% · IPK 5%</strong>.
        Peringkat 1 adalah skor tertinggi (sekaligus rekomendasi sistem).
    </p>
</div>
@endsection
