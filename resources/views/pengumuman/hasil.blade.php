@extends('layouts.app')
@section('title', 'Hasil Konsentrasi')

@section('content')
<div class="space-y-5">

    {{-- ── Hero result card ───────────────────────────────────── --}}
    <div class="rounded-2xl border bg-white dark:bg-gray-900 overflow-hidden"
        style="border-color: {{ $info['color'] }}">

        {{-- Accent bar top --}}
        <div class="h-1 w-full" style="background: {{ $info['color'] }}"></div>

        <div class="p-6 space-y-5">

            {{-- Label konsentrasi --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-widest mb-1.5"
                    style="color: {{ $info['color'] }}">Konsentrasi</p>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $info['label'] }}
                </h1>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100 dark:border-gray-800"></div>

            {{-- Identitas --}}
            <div class="space-y-0.5">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $mahasiswa->nama }}</p>
                <p class="text-xs text-gray-400">{{ $mahasiswa->nim }} &middot; Angkatan {{ $mahasiswa->angkatan }}</p>
            </div>

            {{-- Skor --}}
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-400 font-medium">Skor Akhir</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($mahasiswa->skor_final, 2) }}
                </span>
            </div>

        </div>
    </div>

    {{-- ── Pengumuman resmi ───────────────────────────────────── --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">

        <div class="p-5 space-y-4">

            {{-- Pertimbangan --}}
            <div class="space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    Keputusan ini ditetapkan berdasarkan hasil seleksi yang komprehensif dengan mempertimbangkan:
                </p>
                <ul class="space-y-1.5">
                    @foreach([
                        'Minat dan ketertarikan pada bidang konsentrasi',
                        'Mata kuliah pendukung yang telah ditempuh',
                        'Hasil Tes Potensi Konsentrasi',
                        'Indeks Prestasi Kumulatif (IPK)',
                        'Prestasi akademik dan nonakademik',
                        'Perangkingan hasil seleksi secara keseluruhan',
                    ] as $item)
                    <li class="flex items-center gap-2.5">
                        <span class="shrink-0 w-4 h-4 rounded-full flex items-center justify-center"
                            style="background: {{ $info['color'] }}20">
                            <svg class="w-2.5 h-2.5" style="color: {{ $info['color'] }}" viewBox="0 0 24 24" fill="none">
                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100 dark:border-gray-800"></div>

            {{-- Pesan + Penutup --}}
            <div class="space-y-3">
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ $info['pesan'] }}
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ $info['penutup'] }}
                </p>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100 dark:border-gray-800"></div>

            {{-- Ajakan --}}
            <p class="text-sm font-semibold text-gray-900 dark:text-white text-center py-1">
                Selamat bergabung di Konsentrasi<br>{{ $info['label'] }}!
            </p>

        </div>
    </div>

    {{-- Catatan --}}
    <p class="text-xs text-center text-gray-400">Hasil seleksi bersifat final dan tidak dapat diganggu gugat.</p>

    {{-- ── Tombol kembali ─────────────────────────────────────── --}}
    <a href="{{ route('pengumuman') }}"
        class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
            <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kembali
    </a>

</div>
@endsection
