@extends('layouts.admin')
@section('title', 'Rekap Konsentrasi')
@section('page-title', 'Rekap Konsentrasi Mahasiswa')

@section('content')
<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">Rekap Data Mahasiswa</h2>
            <p class="text-xs text-gray-400 mt-0.5">Gabungan seluruh data pendukung penentuan konsentrasi</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04 9.37C3.04 5.88 5.88 3.04 9.38 3.04c3.5 0 6.33 2.84 6.33 6.33 0 3.5-2.83 6.34-6.33 6.34C5.87 15.71 3.04 12.87 3.04 9.37z" fill="currentColor"/></svg>
                <input type="text" name="search" placeholder="Cari NIM atau nama..." value="{{ request('search') }}"
                    class="w-full h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent pl-9 pr-4 text-sm dark:bg-gray-900 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none">
            </div>
            <select name="angkatan" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300 focus:border-brand-300 focus:outline-none">
                <option value="">Semua Angkatan</option>
                @foreach($angkatanList as $a)
                <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                @endforeach
            </select>
            <select name="status" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300 focus:border-brand-300 focus:outline-none">
                <option value="">Semua Status</option>
                <option value="lengkap" @selected(request('status')=='lengkap')>Data Lengkap</option>
                <option value="belum" @selected(request('status')=='belum')>Belum Lengkap</option>
            </select>
            <button class="h-10 px-4 rounded-lg bg-brand-500 text-white text-sm font-medium hover:bg-brand-600 transition-colors">Cari</button>
            @if(request()->hasAny(['search','angkatan','status']))
            <a href="{{ route('admin.rekap.index') }}" class="h-10 px-4 flex items-center rounded-lg border border-gray-300 dark:border-gray-700 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    <th class="px-6 py-3 text-left">Mahasiswa</th>
                    <th class="px-3 py-3 text-center">Pilihan 1-2-3</th>
                    <th class="px-3 py-3 text-center">IPK</th>
                    <th class="px-3 py-3 text-center">Nilai MK</th>
                    <th class="px-3 py-3 text-center">Tes Minat</th>
                    <th class="px-3 py-3 text-center">Tes Bakat</th>
                    <th class="px-3 py-3 text-center">Rekomendasi</th>
                    <th class="px-3 py-3 text-center">Prestasi</th>
                    <th class="px-3 py-3 text-center">Skor Final</th>
                    <th class="px-3 py-3 text-center">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @forelse($mahasiswa as $m)
                @php
                    $hasil = $m->hasilTesTerakhir;
                    $rc    = $hasil ? (['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$hasil->rekomendasi] ?? '#6b7280') : null;
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    {{-- Mahasiswa --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-9 h-9 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 text-xs font-bold shrink-0">
                                {{ strtoupper(substr($m->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $m->nama }}</p>
                                <p class="text-xs text-gray-400">{{ $m->nim }} · {{ $m->angkatan }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Pilihan Konsentrasi --}}
                    <td class="px-3 py-4 text-center">
                        @if($m->sudah_pilih_konsentrasi && $m->pilihan_konsentrasi)
                            <div class="flex flex-col items-center gap-0.5">
                                @foreach($m->pilihan_konsentrasi as $i => $k)
                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                    <span class="text-brand-500 font-bold">{{ $i + 1 }}.</span> {{ ucfirst($k) }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- IPK --}}
                    <td class="px-3 py-4 text-center">
                        @if($m->ipk !== null)
                            <span class="inline-flex items-center rounded-md bg-brand-50 dark:bg-brand-500/10 px-2 py-0.5 text-xs font-bold text-brand-600 dark:text-brand-400">{{ number_format($m->ipk, 2) }}</span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Nilai MK --}}
                    <td class="px-3 py-4 text-center">
                        @if($m->sudah_input_nilai)
                            <span class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">✓</span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Tes Minat --}}
                    <td class="px-3 py-4 text-center">
                        @if($m->sudah_tes_minat)
                            <span class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">✓</span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Tes Bakat --}}
                    <td class="px-3 py-4 text-center">
                        @if($m->sudah_tes_bakat)
                            <span class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">✓</span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Rekomendasi --}}
                    <td class="px-3 py-4 text-center">
                        @if($hasil && $hasil->lengkap)
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold" style="background:{{ $rc }}20; color:{{ $rc }}">
                                {{ ucfirst($hasil->rekomendasi) }}
                            </span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Prestasi --}}
                    <td class="px-3 py-4 text-center">
                        @if($m->prestasi_relevan)
                            <span class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">✓</span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Skor Final --}}
                    @php $sk = $m->hitungSkorFinal(); @endphp
                    <td class="px-3 py-4 text-center">
                        @if($sk)
                            @php
                                $rcFinal = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$sk['rekomendasi']];
                                $topScore = $sk['total'][$sk['rekomendasi']];
                            @endphp
                            <div class="flex flex-col items-center gap-0.5">
                                <span class="font-bold text-sm" style="color:{{ $rcFinal }}">{{ $topScore }}</span>
                                <span class="text-[10px] uppercase tracking-wide font-medium" style="color:{{ $rcFinal }}">{{ $sk['rekomendasi'] }}</span>
                            </div>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Detail --}}
                    <td class="px-3 py-4 text-center">
                        <a href="{{ route('admin.rekap.show', $m) }}"
                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 mx-auto">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="py-12 text-center text-sm text-gray-400">Tidak ada data ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-xs text-gray-400">{{ $mahasiswa->total() }} mahasiswa</p>
        {{ $mahasiswa->withQueryString()->links() }}
    </div>
</div>

{{-- Info formula bobot --}}
<div class="mt-4 rounded-xl border border-brand-200 dark:border-brand-900 bg-brand-50/30 dark:bg-brand-900/10 px-4 py-3 flex items-start gap-2.5">
    <svg class="w-4 h-4 text-brand-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
    <p class="text-xs text-brand-700 dark:text-brand-400">
        <strong>Formula skor final:</strong> MINAT 40% · Nilai MK 25% · Tes Minat &amp; Bakat 15% · Prestasi 15% · IPK 5% · Total 100%.
        Skor "Prestasi" diinput admin per mahasiswa di halaman detail.
    </p>
</div>
@endsection
