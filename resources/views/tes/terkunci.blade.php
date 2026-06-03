@extends('layouts.app')
@section('title', 'Tes Belum Dibuka')

@section('content')
<div class="max-w-lg mx-auto text-center py-10">

    @if($status === 'berlangsung')
        {{-- Harusnya tidak pernah sampai di sini, tapi jaga-jaga --}}
        <script>window.location = "{{ route('tes.index') }}"</script>

    @elseif($status === 'belum_dijadwalkan')
        {{-- Admin belum buat jadwal sama sekali --}}
        <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-2xl bg-gray-100 dark:bg-gray-800 mb-6">
            <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Jadwal Tes Belum Ditentukan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Jadwal tes konsentrasi untuk Anda belum ditetapkan. Silakan hubungi Prodi Manajemen untuk informasi lebih lanjut.
        </p>

    @elseif($status === 'belum_mulai')
        {{-- Jadwal ada tapi belum waktunya --}}
        <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-2xl bg-brand-50 dark:bg-brand-500/10 mb-6">
            <svg class="w-10 h-10 text-brand-500" viewBox="0 0 24 24" fill="none">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tes Belum Dimulai</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Tes akan dibuka pada:
        </p>

        {{-- Info jadwal --}}
        <div class="rounded-2xl border border-brand-200 dark:border-brand-900 bg-brand-50 dark:bg-brand-900/20 p-5 mb-6 text-left">
            <div class="space-y-2.5">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Nama</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $jadwal->nama }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Mulai</span>
                    <span class="font-semibold text-brand-600 dark:text-brand-400">
                        {{ $jadwal->tanggal_mulai->translatedFormat('l, d F Y \p\u\k\u\l H:i') }} WITA
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Selesai</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">
                        {{ $jadwal->tanggal_selesai->translatedFormat('l, d F Y \p\u\k\u\l H:i') }} WITA
                    </span>
                </div>
                @if($jadwal->keterangan)
                <div class="pt-2.5 border-t border-brand-200 dark:border-brand-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                        <span class="font-medium">Keterangan:</span> {{ $jadwal->keterangan }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Countdown --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 mb-6"
            x-data="countdown('{{ $jadwal->tanggal_mulai->toISOString() }}')" x-init="start()">
            <p class="text-xs text-gray-400 mb-4 uppercase tracking-wider font-medium">Menghitung Mundur</p>
            <div class="grid grid-cols-4 gap-3">
                @foreach([['hari', 'Hari'], ['jam', 'Jam'], ['menit', 'Menit'], ['detik', 'Detik']] as [$var, $label])
                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white tabular-nums" x-text="{{ $var }}">--</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $label }}</div>
                </div>
                @endforeach
            </div>
            <p x-show="selesai" class="mt-3 text-sm text-success-600 dark:text-success-400 font-medium">
                Waktu tes dimulai! <a href="{{ route('tes.index') }}" class="underline">Klik di sini untuk masuk</a>
            </p>
        </div>

    @elseif($status === 'sudah_berakhir')
        {{-- Waktu tes sudah habis --}}
        <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-2xl bg-error-50 dark:bg-error-500/10 mb-6">
            <svg class="w-10 h-10 text-error-500" viewBox="0 0 24 24" fill="none">
                <path d="M12 8v4M12 16h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Waktu Tes Telah Berakhir</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-5">
            Jadwal tes <strong>{{ $jadwal->nama }}</strong> telah berakhir pada
            <strong>{{ $jadwal->tanggal_selesai->translatedFormat('d F Y, H:i') }} WITA</strong>.
            Silakan hubungi Prodi Manajemen untuk informasi lebih lanjut.
        </p>
    @endif

    <form action="{{ route('logout.mahasiswa') }}" method="POST" class="inline mt-2">
        @csrf
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M15 3H7C5.895 3 5 3.895 5 5v14c0 1.105.895 2 2 2h8M19 12H9M16 9l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Keluar
        </button>
    </form>
</div>
@endsection

@if($status === 'belum_mulai')
@push('scripts')
<script>
function countdown(targetISO) {
    return {
        hari: '--', jam: '--', menit: '--', detik: '--',
        selesai: false,
        target: new Date(targetISO),
        timer: null,
        start() {
            this.tick();
            this.timer = setInterval(() => this.tick(), 1000);
        },
        tick() {
            const diff = this.target - new Date();
            if (diff <= 0) {
                this.hari = '00'; this.jam = '00'; this.menit = '00'; this.detik = '00';
                this.selesai = true;
                clearInterval(this.timer);
                // Auto-refresh setelah 3 detik
                setTimeout(() => window.location.reload(), 3000);
                return;
            }
            this.hari   = String(Math.floor(diff / 86400000)).padStart(2, '0');
            this.jam    = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            this.menit  = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            this.detik  = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
    };
}
</script>
@endpush
@endif
