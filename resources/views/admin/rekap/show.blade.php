@extends('layouts.admin')
@section('title', 'Detail Rekap')
@section('page-title', 'Detail Rekap Konsentrasi')

@section('content')
@php
    $hasil = $mahasiswa->hasilTesTerakhir;
    $rc    = $hasil ? (['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$hasil->rekomendasi] ?? '#6b7280') : '#6b7280';
@endphp

<a href="{{ route('admin.rekap.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Kembali ke Rekap
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Identitas --}}
    <div class="lg:col-span-3 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl text-white text-xl font-bold shrink-0" style="background:{{ $rc }}">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-gray-900 dark:text-white text-lg">{{ $mahasiswa->nama }}</h2>
                <p class="text-sm text-gray-400">{{ $mahasiswa->nim }} · Angkatan {{ $mahasiswa->angkatan }}</p>
            </div>
        </div>
    </div>

    {{-- 1. Pilihan Konsentrasi --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">1. Pilihan Konsentrasi</h3>
        @if($mahasiswa->sudah_pilih_konsentrasi && $mahasiswa->pilihan_konsentrasi)
            <div class="space-y-2">
                @foreach($mahasiswa->pilihan_konsentrasi as $i => $k)
                <div class="flex items-center gap-2.5 text-sm">
                    <span class="flex items-center justify-center w-6 h-6 rounded-md bg-brand-500 text-white text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ \App\Models\Mahasiswa::labelKonsentrasi($k) }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum dipilih</p>
        @endif
    </div>

    {{-- 2. IPK --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">2. IPK</h3>
        @if($mahasiswa->ipk !== null)
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-bold text-brand-500">{{ number_format($mahasiswa->ipk, 2) }}</span>
                <span class="text-sm text-gray-400">/ 4.00</span>
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum diisi</p>
        @endif
    </div>

    {{-- 3. Nilai Mata Kuliah --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">3. Nilai Mata Kuliah</h3>
        @if($mkData)
            <div class="space-y-2">
                @foreach($mkData as $mk)
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600 dark:text-gray-400">{{ $mk['label'] }}</span>
                    <span class="font-bold rounded-md px-2 py-0.5" style="background:{{ $mk['warna'] }}15; color:{{ $mk['warna'] }}">{{ $mk['avg'] }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum diisi</p>
        @endif
    </div>

    {{-- 4. Tes Minat & Bakat --}}
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">4. Tes Minat &amp; Bakat</h3>
        @if($hasil && $hasil->lengkap)
            <div class="space-y-3">
                @foreach(['Pemasaran'=>[$hasil->nilai_pemasaran,'#465fff','pemasaran'],'Keuangan'=>[$hasil->nilai_keuangan,'#12b76a','keuangan'],'SDM'=>[$hasil->nilai_sdm,'#f79009','sdm']] as $lbl => [$val, $c, $key])
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $lbl }}</span>
                        <span class="font-bold" style="color:{{ $c }}">{{ number_format($val, 2) }}@if($key === $hasil->rekomendasi)<span class="ml-1 text-warning-500">★</span>@endif</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full" style="width:{{ $val }}%; background:{{ $c }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                <span class="text-xs text-gray-400">Rekomendasi sistem: </span>
                <span class="font-bold text-sm" style="color:{{ $rc }}">{{ $hasil->label_rekomendasi }}</span>
            </div>
        @else
            <p class="text-xs text-gray-400 italic">
                @if(!$mahasiswa->sudah_tes_minat && !$mahasiswa->sudah_tes_bakat)
                    Belum mengerjakan kedua tes
                @elseif(!$mahasiswa->sudah_tes_minat)
                    Tes Minat belum selesai
                @else
                    Tes Bakat belum selesai
                @endif
            </p>
        @endif
    </div>

    {{-- 5. Prestasi Relevan --}}
    @php $prestasi = $mahasiswa->prestasi_relevan ?? null; @endphp
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm">5. Prestasi Relevan</h3>
            <a href="{{ route('admin.prestasi.edit', $mahasiswa) }}"
                class="text-xs text-brand-500 hover:text-brand-600 font-medium inline-flex items-center gap-1">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Edit
            </a>
        </div>
        @if($prestasi)
            <div class="space-y-2">
                @foreach([['Pemasaran','pemasaran','#465fff'],['Keuangan','keuangan','#12b76a'],['SDM','sdm','#f79009']] as [$lbl, $k, $c])
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600 dark:text-gray-400">{{ $lbl }}</span>
                    <span class="font-bold rounded-md px-2 py-0.5" style="background:{{ $c }}15; color:{{ $c }}">{{ $prestasi[$k] ?? 0 }} / 15</span>
                </div>
                @endforeach
            </div>
            @if($mahasiswa->catatan_prestasi)
            <p class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400 italic">
                "{{ $mahasiswa->catatan_prestasi }}"
            </p>
            @endif
        @else
            <p class="text-xs text-gray-400 italic mb-3">Belum diinput admin</p>
            <a href="{{ route('admin.prestasi.edit', $mahasiswa) }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-brand-50 dark:bg-brand-500/10 px-3 py-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                Input Sekarang
            </a>
        @endif
    </div>

    {{-- 6. Skor Final (breakdown lengkap) --}}
    @php $skor = $mahasiswa->hitungSkorFinal(); @endphp
    @if($skor)
    <div class="lg:col-span-3 rounded-2xl border border-brand-200 dark:border-brand-900 bg-white dark:bg-gray-900 p-5">
        <div class="flex items-center justify-between mb-1">
            <h3 class="font-bold text-gray-900 dark:text-white">Skor Penentuan Konsentrasi</h3>
            @php $rcFinal = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$skor['rekomendasi']]; @endphp
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                style="background:{{ $rcFinal }}20; color:{{ $rcFinal }}">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ \App\Models\Mahasiswa::labelKonsentrasi($skor['rekomendasi']) }}
            </span>
        </div>
        <p class="text-xs text-gray-400 mb-4">Berdasarkan formula bobot: MINAT 40% · Nilai MK 25% · Tes Minat&amp;Bakat 15% · Prestasi 15% · IPK 5%</p>

        {{-- Tabel breakdown --}}
        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-3 py-2.5 text-left">Komponen</th>
                        <th class="px-3 py-2.5 text-center">Bobot</th>
                        <th class="px-3 py-2.5 text-center" style="color:#465fff">Pemasaran</th>
                        <th class="px-3 py-2.5 text-center" style="color:#12b76a">Keuangan</th>
                        <th class="px-3 py-2.5 text-center" style="color:#f79009">SDM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach([
                        ['minat',    'MINAT (Pilihan)',       '40%'],
                        ['matkul',   'Nilai MK Pendukung',    '25%'],
                        ['tes',      'Tes Minat & Bakat',     '15%'],
                        ['prestasi', 'Prestasi Relevan',      '15%'],
                        ['ipk',      'IPK',                   '5%'],
                    ] as [$key, $lbl, $bobot])
                    <tr>
                        <td class="px-3 py-2.5 text-xs text-gray-700 dark:text-gray-300">{{ $lbl }}</td>
                        <td class="px-3 py-2.5 text-center text-xs text-gray-400">{{ $bobot }}</td>
                        @foreach(['pemasaran','keuangan','sdm'] as $k)
                        @php $d = $skor['breakdown'][$key][$k] ?? null; @endphp
                        <td class="px-3 py-2.5 text-center text-xs">
                            @if($d)
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $d['kontribusi'] }}</span>
                                <span class="text-gray-400">({{ $d['mentah'] }})</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    {{-- Total row --}}
                    <tr class="bg-gray-50 dark:bg-gray-800/40 font-bold">
                        <td class="px-3 py-3 text-xs uppercase">TOTAL</td>
                        <td class="px-3 py-3 text-center text-xs text-gray-400">100%</td>
                        @foreach(['pemasaran','keuangan','sdm'] as $k)
                        @php $c = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$k]; @endphp
                        <td class="px-3 py-3 text-center">
                            <span class="text-base font-bold" style="color:{{ $c }}">{{ $skor['total'][$k] }}</span>
                            @if($k === $skor['rekomendasi'])
                            <svg class="inline-block w-3.5 h-3.5 ml-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Status kelengkapan data --}}
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach([
                ['Pilihan',    $skor['lengkap']['pilihan']],
                ['Nilai MK',   $skor['lengkap']['matkul']],
                ['IPK',        $skor['lengkap']['ipk']],
                ['Tes M&B',    $skor['lengkap']['tes']],
                ['Prestasi',   $skor['lengkap']['prestasi']],
            ] as [$lbl, $ok])
            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium
                {{ $ok ? 'bg-success-50 dark:bg-success-500/10 text-success-600 dark:text-success-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-400' }}">
                {!! $ok ? '✓' : '○' !!} {{ $lbl }}
            </span>
            @endforeach
        </div>

        @php $belumLengkap = collect($skor['lengkap'])->contains(false); @endphp
        @if($belumLengkap)
        <div class="mt-3 rounded-lg bg-warning-50 dark:bg-warning-900/20 px-3 py-2 text-xs text-warning-700 dark:text-warning-400">
            ⚠ Beberapa data belum lengkap — komponen yang kosong dianggap 0.
        </div>
        @endif
    </div>
    @endif

</div>
@endsection
