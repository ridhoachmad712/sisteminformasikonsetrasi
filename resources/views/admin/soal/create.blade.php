@extends('layouts.admin')
@section('title', 'Tambah Soal')
@section('page-title', 'Tambah Soal')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.soal.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali ke Daftar Soal
    </a>
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Tambah Soal Baru</h2>
        <p class="text-sm text-gray-400 mb-6">Tambahkan pernyataan tes minat atau bakat.</p>
        <form action="{{ route('admin.soal.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Teks Pernyataan <span class="text-error-500">*</span></label>
                <textarea name="teks" rows="3" required placeholder="Masukkan teks pernyataan..."
                    class="@error('teks') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-3 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">{{ old('teks') }}</textarea>
                @error('teks')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis Tes <span class="text-error-500">*</span></label>
                    <select name="jenis" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-3 focus:outline-none dark:bg-gray-900">
                        <option value="">Pilih Jenis</option>
                        <option value="minat" @selected(old('jenis')=='minat')>Tes Minat (60%)</option>
                        <option value="bakat" @selected(old('jenis')=='bakat')>Tes Bakat (40%)</option>
                    </select>
                    @error('jenis')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konsentrasi <span class="text-error-500">*</span></label>
                    <select name="konsentrasi" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-3 focus:outline-none dark:bg-gray-900">
                        <option value="">Pilih Konsentrasi</option>
                        <option value="pemasaran" @selected(old('konsentrasi')=='pemasaran')>Manajemen Pemasaran</option>
                        <option value="keuangan" @selected(old('konsentrasi')=='keuangan')>Manajemen Keuangan</option>
                        <option value="sdm" @selected(old('konsentrasi')=='sdm')>Manajemen SDM</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Simpan Soal
                </button>
                <a href="{{ route('admin.soal.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
