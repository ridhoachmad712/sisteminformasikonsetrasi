@extends('layouts.admin')
@section('title', 'Kelola Soal')
@section('page-title', 'Kelola Soal')

@section('content')
<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">Bank Soal</h2>
            <p class="text-xs text-gray-400 mt-0.5">Kelola pernyataan tes minat dan bakat</p>
        </div>
        <a href="{{ route('admin.soal.create') }}"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-theme-xs transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            Tambah Soal
        </a>
    </div>

    {{-- Status soal minimum --}}
    @php $adaWarning = collect($statusSoal)->contains(fn($s) => !$s['ok_minat'] || !$s['ok_bakat']); @endphp
    @if($adaWarning)
    <div class="px-6 py-3 border-b border-warning-200 dark:border-warning-900 bg-warning-50 dark:bg-warning-900/20">
        <div class="flex items-start gap-2">
            <svg class="w-4 h-4 text-warning-600 dark:text-warning-400 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <div class="text-xs text-warning-700 dark:text-warning-400">
                <span class="font-semibold">Perhatian: </span>Beberapa konsentrasi kekurangan soal aktif. Tes tidak akan bisa dijalankan sampai diperbaiki.
                <div class="mt-1.5 flex flex-wrap gap-2">
                    @foreach($statusSoal as $k => $s)
                        @if(!$s['ok_minat'])
                        <span class="inline-flex items-center rounded-full bg-warning-100 dark:bg-warning-900/40 px-2 py-0.5 font-medium">
                            {{ ucfirst($k) }} Minat: {{ $s['minat'] }}/5 soal
                        </span>
                        @endif
                        @if(!$s['ok_bakat'])
                        <span class="inline-flex items-center rounded-full bg-warning-100 dark:bg-warning-900/40 px-2 py-0.5 font-medium">
                            {{ ucfirst($k) }} Bakat: {{ $s['bakat'] }}/3 soal
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="jenis" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm text-gray-700 dark:text-gray-300 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                <option value="">Semua Jenis</option>
                <option value="minat" @selected(request('jenis')=='minat')>Tes Minat</option>
                <option value="bakat" @selected(request('jenis')=='bakat')>Tes Bakat</option>
            </select>
            <select name="konsentrasi" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm text-gray-700 dark:text-gray-300 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                <option value="">Semua Konsentrasi</option>
                <option value="pemasaran" @selected(request('konsentrasi')=='pemasaran')>Pemasaran</option>
                <option value="keuangan" @selected(request('konsentrasi')=='keuangan')>Keuangan</option>
                <option value="sdm" @selected(request('konsentrasi')=='sdm')>SDM</option>
            </select>
            <button type="submit" class="h-10 px-4 rounded-lg bg-brand-500 text-white text-sm font-medium hover:bg-brand-600 transition-colors">Filter</button>
            @if(request('jenis') || request('konsentrasi'))
            <a href="{{ route('admin.soal.index') }}" class="h-10 px-4 flex items-center rounded-lg border border-gray-300 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 w-8">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Pernyataan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden sm:table-cell">Jenis</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Konsentrasi</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden md:table-cell">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @forelse($soal as $s)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4 text-xs text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-4">
                        <p class="text-gray-800 dark:text-gray-200 line-clamp-2 max-w-sm">{{ $s->teks }}</p>
                    </td>
                    <td class="px-4 py-4 text-center hidden sm:table-cell">
                        @php $jBg = $s->jenis === 'minat' ? 'bg-blue-light-50 dark:bg-blue-light-500/10 text-blue-light-600 dark:text-blue-light-400' : 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400'; @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $jBg }}">
                            {{ ucfirst($s->jenis) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @php $kColors = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009']; $kc = $kColors[$s->konsentrasi] ?? '#6b7280'; @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium" style="background:{{ $kc }}20; color:{{ $kc }}">
                            {{ ucfirst($s->konsentrasi) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center hidden md:table-cell">
                        @if($s->aktif)
                        <span class="inline-flex items-center rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-1 text-xs font-medium text-success-600 dark:text-success-400">Aktif</span>
                        @else
                        <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-500">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.soal.edit', $s) }}"
                                class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <form action="{{ route('admin.soal.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Hapus soal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg border border-error-200 dark:border-error-900 text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m3 0v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6h16z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-sm text-gray-400">Tidak ada soal ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-xs text-gray-400">{{ $soal->total() }} soal total</p>
        {{ $soal->withQueryString()->links() }}
    </div>
</div>
@endsection
