@extends('layouts.admin')
@section('title', 'Buat Jadwal Tes')
@section('page-title', 'Buat Jadwal Tes')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali
    </a>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Buat Jadwal Tes Baru</h2>
        <p class="text-sm text-gray-400 mb-6">Tentukan kapan mahasiswa dapat mengakses tes konsentrasi.</p>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama Jadwal <span class="text-error-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    placeholder="Contoh: Tes Konsentrasi Angkatan 2023 Semester Ganjil"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 dark:placeholder:text-white/30 focus:ring-3 focus:outline-none dark:bg-gray-900">
                @error('nama')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jenis Tes <span class="text-error-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        ['minat', 'Tes Minat', 'Khusus untuk soal minat saja', '#465fff'],
                        ['bakat', 'Tes Bakat', 'Khusus untuk soal bakat saja', '#12b76a'],
                    ] as [$val, $lbl, $desc, $clr])
                    <label class="cursor-pointer" x-data>
                        <input type="radio" name="jenis_tes" value="{{ $val }}" class="sr-only peer"
                            {{ old('jenis_tes', '') === $val ? 'checked' : '' }}>
                        <div class="rounded-xl border-2 border-gray-200 dark:border-gray-700 p-3 text-center transition-all
                            peer-checked:border-current peer-checked:bg-gray-50 dark:peer-checked:bg-gray-800"
                            style="--tw-text-opacity:1">
                            <div class="w-3 h-3 rounded-full mx-auto mb-2" style="background:{{ $clr }}"></div>
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $lbl }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $desc }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('jenis_tes')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Angkatan
                    <span class="ml-1 text-xs font-normal text-gray-400">(kosongkan = berlaku semua angkatan)</span>
                </label>
                <select name="angkatan"
                    class="shadow-theme-xs focus:border-brand-300 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-700 dark:text-gray-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                    <option value="">Semua Angkatan (Global)</option>
                    @foreach($angkatanList as $a)
                    <option value="{{ $a }}" @selected(old('angkatan') == $a)>{{ $a }}</option>
                    @endforeach
                </select>
                @error('angkatan')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5"
                x-data="{
                    mulai: '{{ old('tanggal_mulai') }}',
                    durasi: {{ old('durasi', 60) }},
                    get selesai() {
                        if (!this.mulai || !this.durasi) return '—';
                        const d = new Date(this.mulai);
                        d.setMinutes(d.getMinutes() + parseInt(this.durasi));
                        const pad = n => String(n).padStart(2,'0');
                        return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())} WITA`;
                    }
                }">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Tanggal & Waktu Mulai <span class="text-error-500">*</span>
                    </label>
                    <input type="datetime-local" name="tanggal_mulai" x-model="mulai" required
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                    @error('tanggal_mulai')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Durasi (menit) <span class="text-error-500">*</span>
                    </label>
                    <input type="number" name="durasi" x-model="durasi" min="5" max="1440" step="5" required
                        placeholder="60"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:ring-3 focus:outline-none dark:bg-gray-900">
                    <p class="mt-1 text-xs text-gray-400">
                        Selesai otomatis: <span class="font-medium text-brand-600 dark:text-brand-400" x-text="selesai">—</span>
                    </p>
                    @error('durasi')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Keterangan / Pesan untuk Mahasiswa
                    <span class="ml-1 text-xs font-normal text-gray-400">(opsional)</span>
                </label>
                <textarea name="keterangan" rows="3" placeholder="Contoh: Tes dilaksanakan di Lab Komputer Gedung A. Hadir 15 menit sebelum jadwal."
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-3 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:ring-3 focus:outline-none dark:bg-gray-900">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="hidden" name="aktif" value="0">
                        <input type="checkbox" name="aktif" value="1" checked
                            class="sr-only peer" id="aktif-toggle">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-500"></div>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-400">Aktifkan Jadwal</span>
                        <p class="text-xs text-gray-400">Nonaktifkan jika ingin simpan dulu tanpa langsung berlaku.</p>
                    </div>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwal.index') }}"
                    class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
