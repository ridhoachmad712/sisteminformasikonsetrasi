@extends('layouts.app')
@section('title', 'Nilai Mata Kuliah')

@section('content')
<div class="space-y-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-gray-400">
        <a href="{{ route('beranda') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Beranda</a>
        <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <span class="font-medium text-gray-700 dark:text-gray-300">Nilai Mata Kuliah</span>
    </div>

    {{-- Header --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h1 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500" viewBox="0 0 24 24" fill="none"><path d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.42a12 12 0 01.84 4.42 12 12 0 01-7 .91 12 12 0 01-7-.91 12 12 0 01.84-4.42L12 14z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Input Nilai Akademik
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Masukkan IPK dan nilai akhir (huruf) 9 mata kuliah berikut. Data ini menjadi pertimbangan pendukung rekomendasi konsentrasi Anda.
        </p>
        @if($mahasiswa->sudah_input_nilai)
        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-success-50 dark:bg-success-500/10 px-3 py-1 text-xs font-medium text-success-600 dark:text-success-400">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Sudah diisi — Anda dapat memperbarui kapan saja
        </div>
        @endif
    </div>

    <form action="{{ route('nilai.store') }}" method="POST" class="space-y-4"
        x-data="{
            ipk: '{{ old('ipk', $mahasiswa->ipk) }}',
            dosenPa: '{{ old('dosen_pa_id', $mahasiswa->dosen_pa_id) }}',
            nilai: {!! json_encode(collect($mataKuliah)->keys()->mapWithKeys(fn($k) => [$k => old("nilai.$k", $nilai[$k] ?? '')])->toArray()) !!},
            pernyataan: false,
            totalMk: {{ count($mataKuliah) }},
            get mkTerisi() {
                return Object.values(this.nilai).filter(v => v !== '' && v !== null && v !== undefined).length;
            },
            get bisaSubmit() {
                return this.ipk !== '' &&
                       this.dosenPa !== '' &&
                       this.mkTerisi === this.totalMk &&
                       this.pernyataan;
            },
            get sisaMk() { return this.totalMk - this.mkTerisi; }
        }">
        @csrf

        {{-- IPK --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <label for="ipk" class="block text-sm font-semibold text-gray-800 dark:text-white mb-1">
                Indeks Prestasi Kumulatif (IPK)
            </label>
            <p class="text-xs text-gray-400 mb-3">Masukkan IPK terakhir Anda (skala 0,00 – 4,00). Gunakan tanda <span class="font-semibold text-gray-500 dark:text-gray-300">titik (.)</span> sebagai pemisah desimal, contoh: <span class="font-semibold text-gray-500 dark:text-gray-300">3.45</span></p>
            <input type="number" name="ipk" id="ipk" step="0.01" min="0" max="4"
                value="{{ old('ipk', $mahasiswa->ipk) }}" required placeholder="Contoh: 3.45"
                x-model="ipk"
                class="w-40 h-11 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm font-semibold text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
            @error('ipk')<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>

        {{-- Dosen PA --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <label for="dosen_pa_id" class="block text-sm font-semibold text-gray-800 dark:text-white mb-1">
                Dosen Pembimbing Akademik (PA)
            </label>
            <p class="text-xs text-gray-400 mb-3">Pilih Dosen Penasehat Akademik Anda.</p>
            <select name="dosen_pa_id" id="dosen_pa_id" required
                x-model="dosenPa"
                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                <option value="">— Pilih Dosen PA —</option>
                @foreach($dosenList as $dosen)
                <option value="{{ $dosen->id }}"
                    @selected(old('dosen_pa_id', $mahasiswa->dosen_pa_id) == $dosen->id)>
                    {{ $dosen->nama }}{{ $dosen->nip ? ' — ' . $dosen->nip : '' }}
                </option>
                @endforeach
            </select>
            @error('dosen_pa_id')<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>@enderror
        </div>

        {{-- Daftar Mata Kuliah --}}
        <div class="px-1 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nilai Mata Kuliah</h2>
                <p class="text-xs text-gray-400">Pilih nilai huruf untuk tiap mata kuliah.</p>
            </div>
            <span class="text-xs font-medium px-2.5 py-1 rounded-full"
                :class="mkTerisi === totalMk
                    ? 'bg-success-100 dark:bg-success-500/20 text-success-700 dark:text-success-400'
                    : 'bg-warning-100 dark:bg-warning-500/20 text-warning-700 dark:text-warning-400'">
                <span x-text="mkTerisi"></span>/<span x-text="totalMk"></span> terisi
            </span>
        </div>
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @foreach($mataKuliah as $key => $namaMk)
                <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                    <label for="nilai_{{ $key }}" class="text-sm text-gray-700 dark:text-gray-300 flex-1">
                        <span class="text-gray-400 mr-1.5">{{ $loop->iteration }}.</span>{{ $namaMk }}
                    </label>
                    <select name="nilai[{{ $key }}]" id="nilai_{{ $key }}" required
                        x-model="nilai['{{ $key }}']"
                        class="shrink-0 w-24 h-10 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm font-semibold text-gray-800 dark:text-white focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-none dark:bg-gray-900">
                        <option value="">—</option>
                        @foreach($pilihan as $p)
                        <option value="{{ $p }}" @selected(old("nilai.$key", $nilai[$key] ?? '') === $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pernyataan Integritas --}}
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="pernyataan" id="pernyataan" required
                    x-model="pernyataan"
                    class="mt-0.5 w-5 h-5 shrink-0 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500/20 cursor-pointer">
                <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    Saya menyatakan bahwa data yang saya input di atas adalah data yang <span class="font-semibold text-gray-900 dark:text-white">sebenar-benarnya</span>. Apabila di kemudian hari ditemukan perbedaan atau ketidaksesuaian dengan data resmi, saya bersedia menerima <span class="font-semibold text-gray-900 dark:text-white">sanksi akademik</span> yang berlaku.
                </span>
            </label>
            @error('pernyataan')<p class="mt-2 text-xs text-error-500 ml-8">{{ $message }}</p>@enderror
        </div>

        {{-- Submit --}}
        <div class="flex gap-3 pb-2">
            <a href="{{ route('beranda') }}" class="h-12 px-5 flex items-center justify-center rounded-xl border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                Batal
            </a>
            <button type="submit"
                :disabled="!bisaSubmit"
                :class="!bisaSubmit ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'"
                class="flex-1 h-12 rounded-xl bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                Simpan Nilai
            </button>
        </div>
    </form>

</div>
@endsection
