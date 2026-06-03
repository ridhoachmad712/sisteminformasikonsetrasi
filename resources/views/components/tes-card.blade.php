@props(['jenis', 'judul', 'deskripsi', 'status', 'jadwal', 'mahasiswa', 'icon_color' => '#465fff', 'route_name'])

@php
$icons = [
    'minat' => '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
    'bakat' => '<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
];

$statusConfig = match($status) {
    'selesai'          => ['bg' => 'bg-success-50 dark:bg-success-500/10', 'border' => 'border-success-200 dark:border-success-900', 'badge' => 'bg-success-100 dark:bg-success-500/20 text-success-600 dark:text-success-400', 'label' => 'Selesai'],
    'berlangsung'      => ['bg' => 'bg-white dark:bg-gray-900', 'border' => 'border-brand-200 dark:border-brand-900', 'badge' => 'bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400', 'label' => 'Sedang Berlangsung'],
    'belum_mulai'      => ['bg' => 'bg-white dark:bg-gray-900', 'border' => 'border-gray-200 dark:border-gray-800', 'badge' => 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400', 'label' => 'Belum Dimulai'],
    'sudah_berakhir'   => ['bg' => 'bg-gray-50 dark:bg-gray-800/30', 'border' => 'border-gray-200 dark:border-gray-800', 'badge' => 'bg-gray-100 dark:bg-gray-800 text-gray-400', 'label' => 'Waktu Habis'],
    'belum_dijadwalkan'=> ['bg' => 'bg-gray-50 dark:bg-gray-800/30', 'border' => 'border-gray-200 dark:border-gray-800', 'badge' => 'bg-gray-100 dark:bg-gray-800 text-gray-400', 'label' => 'Belum Dijadwalkan'],
    default            => ['bg' => 'bg-white dark:bg-gray-900', 'border' => 'border-gray-200 dark:border-gray-800', 'badge' => 'bg-gray-100 text-gray-500', 'label' => ucfirst($status)],
};
@endphp

<div class="rounded-2xl border {{ $statusConfig['border'] }} {{ $statusConfig['bg'] }} overflow-hidden">
    <div class="p-5">
        <div class="flex items-start gap-4">
            {{-- Icon --}}
            <div class="flex items-center justify-center w-12 h-12 rounded-2xl shrink-0"
                style="background: {{ $icon_color }}20; border: 1.5px solid {{ $icon_color }}30">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" style="color: {{ $icon_color }}">
                    {!! $icons[$jenis] ?? '' !!}
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $judul }}</h3>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusConfig['badge'] }}">
                        @if($status === 'berlangsung')
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse" style="background:{{ $icon_color }}"></span>
                        @endif
                        {{ $statusConfig['label'] }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{{ $deskripsi }}</p>
            </div>
        </div>

        {{-- Jadwal info --}}
        @if($jadwal && in_array($status, ['berlangsung','belum_mulai','sudah_berakhir']))
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 grid grid-cols-2 gap-3 text-xs">
            <div>
                <span class="text-gray-400">Mulai</span>
                <p class="font-medium text-gray-700 dark:text-gray-300 mt-0.5">
                    {{ $jadwal->tanggal_mulai->format('d M Y, H:i') }} WITA
                </p>
            </div>
            <div>
                <span class="text-gray-400">Selesai</span>
                <p class="font-medium text-gray-700 dark:text-gray-300 mt-0.5">
                    {{ $jadwal->tanggal_selesai->format('d M Y, H:i') }} WITA
                </p>
            </div>
            @if($jadwal->keterangan)
            <div class="col-span-2">
                <span class="text-gray-400">Keterangan</span>
                <p class="text-gray-600 dark:text-gray-400 mt-0.5">{{ $jadwal->keterangan }}</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Countdown jika belum mulai --}}
        @if($status === 'belum_mulai' && $jadwal)
        <div class="mt-4" x-data="countdown('{{ $jadwal->tanggal_mulai->toISOString() }}')" x-init="start()">
            <p class="text-xs text-gray-400 mb-2">Dimulai dalam:</p>
            <div class="flex gap-2">
                @foreach([['hari','Hari'],['jam','Jam'],['menit','Mnt'],['detik','Dtk']] as [$v,$l])
                <div class="flex-1 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 py-2 text-center">
                    <div class="text-xl font-bold text-gray-900 dark:text-white tabular-nums" x-text="{{ $v }}">--</div>
                    <div class="text-xs text-gray-400">{{ $l }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CTA Button --}}
        <div class="mt-4">
            @if($status === 'selesai')
                <div class="inline-flex items-center gap-2 rounded-xl bg-success-50 dark:bg-success-500/10 px-4 py-2 text-sm font-medium text-success-600 dark:text-success-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg>
                    Sudah diselesaikan
                </div>

            @elseif($status === 'berlangsung')
                @php
                    $draftKey   = "draft_{$jenis}";
                    $draftCount = count($mahasiswa->$draftKey ?? []);
                @endphp
                <a href="{{ route($route_name) }}"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition-colors"
                    style="background: {{ $icon_color }}">
                    @if($draftCount > 0)
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                        Lanjutkan ({{ $draftCount }} terjawab)
                    @else
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Mulai {{ $judul }}
                    @endif
                </a>

            @else
                <button disabled
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-100 dark:bg-gray-800 px-5 py-2.5 text-sm font-medium text-gray-400 dark:text-gray-500 cursor-not-allowed">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @if($status === 'belum_mulai') Menunggu Jadwal
                    @elseif($status === 'sudah_berakhir') Waktu Habis
                    @else Belum Dijadwalkan
                    @endif
                </button>
            @endif
        </div>
    </div>
</div>

@if($status === 'belum_mulai' && $jadwal)
@once
@push('scripts')
<script>
function countdown(targetISO) {
    return {
        hari:'--', jam:'--', menit:'--', detik:'--', selesai:false,
        target: new Date(targetISO), timer: null,
        start() { this.tick(); this.timer = setInterval(() => this.tick(), 1000); },
        tick() {
            const diff = this.target - new Date();
            if (diff <= 0) {
                this.hari='00'; this.jam='00'; this.menit='00'; this.detik='00';
                this.selesai = true; clearInterval(this.timer);
                setTimeout(() => window.location.reload(), 2000);
                return;
            }
            this.hari   = String(Math.floor(diff/86400000)).padStart(2,'0');
            this.jam    = String(Math.floor(diff%86400000/3600000)).padStart(2,'0');
            this.menit  = String(Math.floor(diff%3600000/60000)).padStart(2,'0');
            this.detik  = String(Math.floor(diff%60000/1000)).padStart(2,'0');
        }
    };
}
</script>
@endpush
@endonce
@endif
