@extends('layouts.admin')
@section('title', 'Edit Soal')
@section('page-title', 'Edit Soal')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.soal.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali
    </a>
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Edit Soal</h2>
        <p class="text-sm text-gray-400 mb-6">Perbarui pernyataan tes.</p>
        <form action="{{ route('admin.soal.update', $soal) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Teks Pernyataan <span class="text-error-500">*</span></label>
                <textarea name="teks" rows="3" required
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-3 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">{{ old('teks', $soal->teks) }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Tes</label>
                    <select name="jenis" class="shadow-theme-xs focus:border-brand-300 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-3 focus:outline-none dark:bg-gray-900">
                        <option value="minat" @selected(old('jenis',$soal->jenis)=='minat')>Tes Minat (60%)</option>
                        <option value="bakat" @selected(old('jenis',$soal->jenis)=='bakat')>Tes Bakat (40%)</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konsentrasi</label>
                    <select name="konsentrasi" class="shadow-theme-xs focus:border-brand-300 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-3 focus:outline-none dark:bg-gray-900">
                        <option value="pemasaran" @selected(old('konsentrasi',$soal->konsentrasi)=='pemasaran')>Pemasaran</option>
                        <option value="keuangan" @selected(old('konsentrasi',$soal->konsentrasi)=='keuangan')>Keuangan</option>
                        <option value="sdm" @selected(old('konsentrasi',$soal->konsentrasi)=='sdm')>SDM</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $soal->urutan) }}" min="0"
                        class="shadow-theme-xs focus:border-brand-300 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                </div>
                <div class="flex items-end pb-2">
                    <label x-data="{on:{{ $soal->aktif ? 'true' : 'false' }}}" class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="aktif" value="0">
                        <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $soal->aktif)) class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-500"></div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">Soal Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Update Soal
                </button>
                <a href="{{ route('admin.soal.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
