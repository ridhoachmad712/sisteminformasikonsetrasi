@extends('layouts.admin')
@section('title', 'Detail Hasil Tes')
@section('page-title', 'Detail Hasil Tes')

@section('content')
@php
$rc = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$hasil->rekomendasi] ?? '#6b7280';
@endphp

<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

    {{-- Left Panel --}}
    <div class="lg:col-span-2 space-y-4">
        <a href="{{ route('admin.hasil.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Kembali
        </a>

        {{-- Profile card --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex items-center justify-center w-14 h-14 rounded-2xl text-white text-xl font-bold" style="background:{{ $rc }}">
                    {{ strtoupper(substr($hasil->mahasiswa->nama, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $hasil->mahasiswa->nama }}</h3>
                    <p class="text-sm text-gray-400">{{ $hasil->mahasiswa->nim }} · {{ $hasil->mahasiswa->angkatan }}</p>
                </div>
            </div>
            <div class="space-y-2.5 text-sm">
                @foreach([['Tanggal Tes', $hasil->created_at->format('d F Y, H:i') . ' WITA'], ['Email', $hasil->mahasiswa->email ?? '—']] as [$label, $val])
                <div class="flex justify-between">
                    <span class="text-gray-400">{{ $label }}</span>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recommendation --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 text-center">
            <p class="text-xs text-gray-400 mb-3">Rekomendasi Konsentrasi</p>
            <div class="inline-flex items-center justify-center rounded-2xl px-5 py-3 mb-2" style="background:{{ $rc }}15; border:1.5px solid {{ $rc }}40">
                <span class="font-bold text-base" style="color:{{ $rc }}">{{ $hasil->label_rekomendasi }}</span>
            </div>
        </div>

        {{-- Score table --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <h4 class="font-bold text-gray-900 dark:text-white mb-4 text-sm">Rincian Skor</h4>
            <div class="overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/50">
                            <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase">Konsentrasi</th>
                            <th class="px-3 py-2.5 text-center text-gray-500 font-semibold uppercase">Minat</th>
                            <th class="px-3 py-2.5 text-center text-gray-500 font-semibold uppercase">Bakat</th>
                            <th class="px-3 py-2.5 text-center text-gray-500 font-semibold uppercase">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach([
                            ['Pemasaran',$hasil->skor_minat_pemasaran,$hasil->skor_bakat_pemasaran,$hasil->nilai_pemasaran,'pemasaran','#465fff'],
                            ['Keuangan',$hasil->skor_minat_keuangan,$hasil->skor_bakat_keuangan,$hasil->nilai_keuangan,'keuangan','#12b76a'],
                            ['SDM',$hasil->skor_minat_sdm,$hasil->skor_bakat_sdm,$hasil->nilai_sdm,'sdm','#f79009'],
                        ] as [$lbl,$m,$b,$n,$k,$c])
                        <tr @if($k === $hasil->rekomendasi) style="background:{{ $c }}08" @endif>
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full shrink-0" style="background:{{ $c }}"></div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $lbl }}</span>
                                    @if($k === $hasil->rekomendasi)<svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endif
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center text-gray-500">{{ $m }}/75</td>
                            <td class="px-3 py-3 text-center text-gray-500">{{ $b }}/50</td>
                            <td class="px-3 py-3 text-center font-bold" style="color:{{ $c }}">{{ number_format($n, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Nilai Mata Kuliah (data pendukung) --}}
        @php $mkData = $hasil->mahasiswa->nilaiMkPerKonsentrasi(); @endphp
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <div class="flex items-center justify-between mb-1">
                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Nilai Akademik Pendukung</h4>
                @if($hasil->mahasiswa->ipk !== null)
                <span class="inline-flex items-center gap-1 rounded-lg bg-brand-50 dark:bg-brand-500/10 px-2.5 py-1 text-xs">
                    <span class="text-gray-400">IPK</span>
                    <span class="font-bold text-brand-600 dark:text-brand-400">{{ number_format($hasil->mahasiswa->ipk, 2) }}</span>
                </span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mb-4">Data pendukung — tidak memengaruhi nilai tes</p>

            @if($mkData)
                @foreach($mkData as $mk)
                <div class="mb-4 last:mb-0">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" style="background:{{ $mk['warna'] }}"></span>
                            <span class="text-xs font-semibold uppercase tracking-wide" style="color:{{ $mk['warna'] }}">{{ $mk['label'] }}</span>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-md" style="background:{{ $mk['warna'] }}15; color:{{ $mk['warna'] }}">
                            Rata² {{ $mk['avg'] }}
                        </span>
                    </div>
                    <div class="space-y-1">
                        @foreach($mk['detail'] as $d)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 dark:text-gray-400">{{ $d['mk'] }}</span>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $d['huruf'] ?? '-' }} <span class="text-gray-400 font-normal">({{ $d['angka'] }})</span></span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-xs text-gray-400 italic py-2">Mahasiswa belum menginput nilai mata kuliah.</p>
            @endif
        </div>
    </div>

    {{-- Right Panel: Jawaban --}}
    <div class="lg:col-span-3">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 h-full">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                    Detail Jawaban
                    <span class="ml-2 text-xs font-normal text-gray-400">({{ $hasil->detailJawaban->count() }} soal)</span>
                </h4>
                <div class="flex gap-1.5">
                    @foreach(['minat'=>['#465fff','Minat'],'bakat'=>['#12b76a','Bakat']] as $jk=>[$jc,$jl])
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium"
                        style="background:{{ $jc }}15; color:{{ $jc }}">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $jc }}"></span>{{ $jl }}
                    </span>
                    @endforeach
                </div>
            </div>
            <div class="overflow-y-auto" style="max-height: 580px;">
                @php $grouped = $hasil->detailJawaban->groupBy(fn($d) => $d->soal->konsentrasi . '_' . $d->soal->jenis); @endphp
                @foreach(['pemasaran_minat'=>['Pemasaran','Minat','#465fff'],'pemasaran_bakat'=>['Pemasaran','Bakat','#465fff'],'keuangan_minat'=>['Keuangan','Minat','#12b76a'],'keuangan_bakat'=>['Keuangan','Bakat','#12b76a'],'sdm_minat'=>['SDM','Minat','#f79009'],'sdm_bakat'=>['SDM','Bakat','#f79009']] as $grp => [$k, $j, $c])
                    @if($grouped->has($grp))
                    <div class="mb-5">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-2.5 h-2.5 rounded-full" style="background:{{ $c }}"></div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $k }} — Tes {{ $j }}</span>
                        </div>
                        <div class="space-y-2">
                            @foreach($grouped[$grp] as $d)
                            <div class="flex items-start gap-3 rounded-xl p-3 bg-gray-50 dark:bg-gray-800/40">
                                <span class="shrink-0 text-xs text-gray-400 mt-0.5 w-4">{{ $loop->iteration }}.</span>
                                <p class="flex-1 text-xs text-gray-700 dark:text-gray-300 leading-relaxed">{{ $d->soal->teks }}</p>
                                <span class="shrink-0 flex items-center justify-center w-7 h-7 rounded-lg text-white text-xs font-bold" style="background:{{ $c }}">{{ $d->nilai }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
