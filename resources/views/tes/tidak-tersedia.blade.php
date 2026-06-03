@extends('layouts.app')
@section('title', 'Tes Tidak Tersedia')

@section('content')
<div class="max-w-md mx-auto text-center py-16">
    <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-warning-50 dark:bg-warning-500/10 mb-6">
        <svg class="w-10 h-10 text-warning-500" viewBox="0 0 24 24" fill="none">
            <path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Tes Belum Tersedia</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
        Sistem tes konsentrasi sedang dalam persiapan. Bank soal belum mencukupi untuk memulai tes. Silakan hubungi administrator program studi.
    </p>
    <form action="{{ route('logout.mahasiswa') }}" method="POST">
        @csrf
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-5 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M15 3H7C5.895 3 5 3.895 5 5v14c0 1.105.895 2 2 2h8M19 12H9M16 9l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Keluar
        </button>
    </form>
</div>
@endsection
