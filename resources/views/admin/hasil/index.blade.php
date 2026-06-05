@extends('layouts.admin')
@section('title', 'Hasil Tes')
@section('page-title', 'Hasil Tes Mahasiswa')

@section('content')
<div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="font-bold text-gray-900 dark:text-white">Rekap Hasil Tes</h2>
            <p class="text-xs text-gray-400 mt-0.5">Hasil tes konsentrasi seluruh mahasiswa</p>
        </div>
        <a href="{{ route('admin.hasil.export', request()->query()) }}"
            class="inline-flex items-center gap-2 rounded-xl border border-success-300 dark:border-success-800 bg-success-50 dark:bg-success-500/10 px-4 py-2.5 text-sm font-semibold text-success-600 dark:text-success-400 hover:bg-success-100 dark:hover:bg-success-500/20 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export CSV
        </a>
    </div>

    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[180px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04 9.37C3.04 5.88 5.88 3.04 9.38 3.04c3.5 0 6.33 2.84 6.33 6.33 0 3.5-2.83 6.34-6.33 6.34C5.87 15.71 3.04 12.87 3.04 9.37zm6.33-7.83C5.05 1.54 1.54 5.05 1.54 9.37c0 4.32 3.51 7.83 7.83 7.83 1.9 0 3.63-.67 4.98-1.78l2.82 2.82a.75.75 0 001.06-1.06l-2.82-2.82A7.78 7.78 0 0017.21 9.37c0-4.32-3.51-7.83-7.84-7.83z" fill="currentColor"/></svg>
                <input type="text" name="search" placeholder="Cari nama atau NIM..." value="{{ request('search') }}"
                    class="w-full h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent pl-9 pr-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
            </div>
            <select name="rekomendasi" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm text-gray-700 dark:text-gray-300 focus:border-brand-300 focus:outline-none dark:bg-gray-900">
                <option value="">Semua Rekomendasi</option>
                <option value="pemasaran" @selected(request('rekomendasi')=='pemasaran')>Pemasaran</option>
                <option value="keuangan" @selected(request('rekomendasi')=='keuangan')>Keuangan</option>
                <option value="sdm" @selected(request('rekomendasi')=='sdm')>SDM</option>
            </select>
            <select name="angkatan" class="h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm text-gray-700 dark:text-gray-300 focus:border-brand-300 focus:outline-none dark:bg-gray-900">
                <option value="">Semua Angkatan</option>
                @foreach($angkatanList as $a)
                <option value="{{ $a }}" @selected(request('angkatan') == $a)>{{ $a }}</option>
                @endforeach
            </select>
            <button type="submit" class="h-10 px-4 rounded-lg bg-brand-500 text-white text-sm font-medium hover:bg-brand-600 transition-colors">Cari</button>
            @if(request()->hasAny(['search','rekomendasi','angkatan']))
            <a href="{{ route('admin.hasil.index') }}" class="h-10 px-4 flex items-center rounded-lg border border-gray-300 dark:border-gray-700 text-sm text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Mahasiswa</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden sm:table-cell">Angkatan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Rekomendasi</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden lg:table-cell">Pemasaran</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden lg:table-cell">Keuangan</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden lg:table-cell">SDM</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden xl:table-cell">IPK</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden xl:table-cell">Pilihan 1-2-3</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden md:table-cell">Tes Selesai</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 hidden md:table-cell">Tanggal</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @forelse($hasil as $h)
                @php $rc = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$h->rekomendasi] ?? '#6b7280'; @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold shrink-0" style="background:{{ $rc }}20; color:{{ $rc }}">
                                {{ strtoupper(substr($h->mahasiswa->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $h->mahasiswa->nama }}</p>
                                <p class="text-gray-400 text-xs">{{ $h->mahasiswa->nim }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center text-xs text-gray-500 hidden sm:table-cell">{{ $h->mahasiswa->angkatan }}</td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold" style="background:{{ $rc }}20; color:{{ $rc }}">
                            {{ ucfirst($h->rekomendasi) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center text-xs font-medium hidden lg:table-cell" style="color:#465fff">{{ number_format($h->nilai_pemasaran, 1) }}</td>
                    <td class="px-4 py-4 text-center text-xs font-medium hidden lg:table-cell" style="color:#12b76a">{{ number_format($h->nilai_keuangan, 1) }}</td>
                    <td class="px-4 py-4 text-center text-xs font-medium hidden lg:table-cell" style="color:#f79009">{{ number_format($h->nilai_sdm, 1) }}</td>
                    {{-- IPK --}}
                    <td class="px-4 py-4 text-center hidden xl:table-cell">
                        @if($h->mahasiswa->ipk !== null)
                            <span class="text-xs font-bold text-brand-600 dark:text-brand-400">{{ number_format($h->mahasiswa->ipk, 2) }}</span>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Pilihan Konsentrasi --}}
                    <td class="px-4 py-4 text-center hidden xl:table-cell">
                        @if($h->mahasiswa->sudah_pilih_konsentrasi && $h->mahasiswa->pilihan_konsentrasi)
                            <div class="flex flex-col items-center gap-0.5">
                                @foreach($h->mahasiswa->pilihan_konsentrasi as $i => $k)
                                <span class="text-xs text-gray-600 dark:text-gray-400 leading-tight">
                                    <span class="font-bold" style="color:{{ ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$k] ?? '#6b7280' }}">{{ $i+1 }}.</span>
                                    {{ \App\Models\Mahasiswa::labelKonsentrasi($k) }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                        @endif
                    </td>

                    <td class="px-4 py-4 text-center hidden md:table-cell">
                        <div class="flex items-center justify-center gap-1">
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $h->sudah_minat ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                                M
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium {{ $h->sudah_bakat ? 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                                B
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-right text-xs text-gray-400 hidden md:table-cell">{{ $h->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.hasil.show', $h) }}"
                            class="flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-800 text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors mx-auto">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="py-12 text-center text-sm text-gray-400">Belum ada hasil tes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 dark:border-gray-800">
        <p class="text-xs text-gray-400">{{ $hasil->total() }} hasil tes</p>
        {{ $hasil->withQueryString()->links() }}
    </div>
</div>
@endsection
