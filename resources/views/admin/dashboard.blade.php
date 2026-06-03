@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    @php
    $stats = [
        ['label'=>'Total Mahasiswa','value'=>$totalMahasiswa,'icon'=>'<path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/','bg'=>'bg-brand-50 dark:bg-brand-500/10','ic'=>'text-brand-500','sub'=>null],
        ['label'=>'Selesai Keduanya','value'=>$sudahTes,'icon'=>'<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/','bg'=>'bg-success-50 dark:bg-success-500/10','ic'=>'text-success-500','sub'=>'Minat ✓ Bakat ✓'],
        ['label'=>'Tes Minat Selesai','value'=>$sudahMinat,'icon'=>'<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/','bg'=>'bg-blue-50 dark:bg-blue-500/10','ic'=>'text-blue-500','sub'=>'dari '.$totalMahasiswa.' mhs'],
        ['label'=>'Tes Bakat Selesai','value'=>$sudahBakat,'icon'=>'<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/','bg'=>'bg-green-50 dark:bg-green-500/10','ic'=>'text-green-500','sub'=>'dari '.$totalMahasiswa.' mhs'],
    ];
    @endphp

    @foreach($stats as $s)
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl {{ $s['bg'] }}">
                <svg class="w-5 h-5 {{ $s['ic'] }}" viewBox="0 0 24 24" fill="none">
                    {!! $s['icon'] !!}
                </svg>
            </div>
        </div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $s['value'] }}</div>
        <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $s['label'] }}</div>
        @if($s['sub'])<div class="text-xs text-gray-400 mt-0.5">{{ $s['sub'] }}</div>@endif
    </div>
    @endforeach
</div>

{{-- Jadwal Tes Widget --}}
@if($jadwalAktif->isNotEmpty())
<div class="mb-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-500" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"/></svg>
            Jadwal Tes Mendatang
        </h3>
        <a href="{{ route('admin.jadwal.index') }}" class="text-xs text-brand-500 hover:text-brand-600 font-medium">Kelola Jadwal →</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-{{ min($jadwalAktif->count(), 3) }} gap-3">
        @foreach($jadwalAktif as $j)
        @php
            $isBerlangsung = $j->sedang_berlangsung;
            $borderColor   = $isBerlangsung ? 'border-success-200 dark:border-success-900' : 'border-gray-200 dark:border-gray-700';
            $bgColor       = $isBerlangsung ? 'bg-success-50 dark:bg-success-900/20' : 'bg-gray-50 dark:bg-gray-800/40';
        @endphp
        <div class="rounded-xl border {{ $borderColor }} {{ $bgColor }} p-4">
            <div class="flex items-start justify-between mb-2">
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 leading-snug">{{ $j->nama }}</span>
                @if($isBerlangsung)
                <span class="ml-2 shrink-0 inline-flex items-center gap-1 rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-600 dark:text-success-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-success-500 animate-pulse"></span>Live
                </span>
                @endif
            </div>
            <div class="space-y-1 text-xs text-gray-500 dark:text-gray-400">
                <div>🎓 {{ $j->angkatan ? 'Angkatan '.$j->angkatan : 'Semua Angkatan' }}</div>
                <div>🕐 {{ $j->tanggal_mulai->format('d/m/Y H:i') }} WITA</div>
                <div>🕕 {{ $j->tanggal_selesai->format('d/m/Y H:i') }} WITA</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
    {{-- Pie Chart --}}
    <div class="lg:col-span-2 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Distribusi Rekomendasi</h3>
        <p class="text-xs text-gray-400 mb-5">Berdasarkan hasil tes mahasiswa</p>

        @php
        $dist = [
            'Pemasaran' => [$distribusi['pemasaran'] ?? 0, '#465fff'],
            'Keuangan'  => [$distribusi['keuangan']  ?? 0, '#12b76a'],
            'SDM'       => [$distribusi['sdm']        ?? 0, '#f79009'],
        ];
        $total = array_sum(array_column($dist, 0));
        @endphp

        <div id="chart-dist" class="mb-4"></div>

        <div class="space-y-2.5">
            @foreach($dist as $label => [$count, $color])
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full shrink-0" style="background:{{ $color }}"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $label }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $count }}</span>
                    @if($total > 0)
                    <span class="text-xs text-gray-400">({{ round($count/$total*100) }}%)</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Results Table --}}
    <div class="lg:col-span-3 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Hasil Tes Terbaru</h3>
                <p class="text-xs text-gray-400 mt-0.5">10 mahasiswa terakhir</p>
            </div>
            <a href="{{ route('admin.hasil.index') }}"
                class="text-xs font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 flex items-center gap-1">
                Lihat Semua
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="pb-3 text-left text-xs font-semibold uppercase text-gray-400">Mahasiswa</th>
                        <th class="pb-3 text-center text-xs font-semibold uppercase text-gray-400">Rekomendasi</th>
                        <th class="pb-3 text-right text-xs font-semibold uppercase text-gray-400">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    @forelse($hasilTerbaru as $h)
                    <tr>
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($h->mahasiswa->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $h->mahasiswa->nama }}</p>
                                    <p class="text-gray-400 text-xs">{{ $h->mahasiswa->nim }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 text-center">
                            @php $rc = ['pemasaran'=>'#465fff','keuangan'=>'#12b76a','sdm'=>'#f79009'][$h->rekomendasi] ?? '#6b7280'; @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
                                style="background:{{ $rc }}20; color:{{ $rc }}">
                                {{ ucfirst($h->rekomendasi) }}
                            </span>
                        </td>
                        <td class="py-3 text-right text-xs text-gray-400">{{ $h->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-8 text-center text-sm text-gray-400">Belum ada data hasil tes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') return;
    const isDark = document.documentElement.classList.contains('dark');
    const options = {
        series: [{{ $distribusi['pemasaran'] ?? 0 }}, {{ $distribusi['keuangan'] ?? 0 }}, {{ $distribusi['sdm'] ?? 0 }}],
        labels: ['Pemasaran','Keuangan','SDM'],
        colors: ['#465fff','#12b76a','#f79009'],
        chart: { type: 'donut', height: 160, background: 'transparent' },
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: { pie: { donut: { size: '65%' } } },
        stroke: { width: 0 },
        theme: { mode: isDark ? 'dark' : 'light' },
    };
    const chart = new ApexCharts(document.getElementById('chart-dist'), options);
    chart.render();
});
</script>
@endpush
