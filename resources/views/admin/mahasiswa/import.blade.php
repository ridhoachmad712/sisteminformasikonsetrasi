@extends('layouts.admin')
@section('title', 'Import Mahasiswa')
@section('page-title', 'Import Mahasiswa')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 mb-5">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Kembali ke Daftar Mahasiswa
    </a>

    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 mb-4">
        <h2 class="font-bold text-gray-900 dark:text-white mb-1">Import Massal via CSV</h2>
        <p class="text-sm text-gray-400 mb-5">Upload file CSV untuk mendaftarkan banyak mahasiswa sekaligus.</p>

        {{-- Info format --}}
        <div class="rounded-xl border border-brand-200 dark:border-brand-900 bg-brand-50 dark:bg-brand-900/20 p-4 mb-5">
            <h3 class="text-sm font-semibold text-brand-700 dark:text-brand-400 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
                Format Kolom CSV
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-brand-200 dark:border-brand-800">
                            <th class="pb-2 text-left text-brand-600 dark:text-brand-400 font-semibold">Kolom</th>
                            <th class="pb-2 text-left text-brand-600 dark:text-brand-400 font-semibold">Isi</th>
                            <th class="pb-2 text-left text-brand-600 dark:text-brand-400 font-semibold">Wajib?</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-100 dark:divide-brand-900/50">
                        @foreach([['nim','Nomor Induk Mahasiswa','Ya'],['nama','Nama lengkap','Ya'],['angkatan','Tahun angkatan (4 digit)','Ya'],['email','Alamat email','Tidak'],['password','Password awal (default: NIM)','Tidak']] as [$k,$d,$w])
                        <tr>
                            <td class="py-1.5 font-mono text-brand-700 dark:text-brand-300">{{ $k }}</td>
                            <td class="py-1.5 text-brand-600 dark:text-brand-400">{{ $d }}</td>
                            <td class="py-1.5"><span class="@if($w==='Ya') text-error-500 font-semibold @else text-gray-400 @endif">{{ $w }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 pt-3 border-t border-brand-200 dark:border-brand-800">
                <a href="{{ route('admin.mahasiswa.import.template') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-brand-600 dark:text-brand-400 hover:underline">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download Template CSV
                </a>
            </div>
        </div>

        <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div x-data="{fileName: ''}" class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    File CSV <span class="text-error-500">*</span>
                </label>
                <div class="relative flex items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 hover:border-brand-400 dark:hover:border-brand-600 transition-colors cursor-pointer bg-gray-50 dark:bg-gray-800/30"
                    @click="$refs.fileInput.click()">
                    <input type="file" name="file" accept=".csv,text/csv" required x-ref="fileInput" class="hidden"
                        @change="fileName = $event.target.files[0]?.name || ''">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <p x-show="!fileName" class="text-sm text-gray-500 dark:text-gray-400">Klik untuk memilih file atau seret ke sini</p>
                        <p x-show="fileName" class="text-sm font-medium text-brand-600 dark:text-brand-400" x-text="fileName"></p>
                        <p class="text-xs text-gray-400 mt-1">Format: .csv · Maks 2MB</p>
                    </div>
                </div>
                @error('file')<p class="mt-1 text-xs text-error-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-brand-500 hover:bg-brand-600 inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Import Sekarang
                </button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 dark:border-gray-700 px-6 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Batal</a>
            </div>
        </form>
    </div>

    {{-- Contoh data --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Contoh isi file CSV:</h3>
        <pre class="text-xs bg-gray-50 dark:bg-gray-800 rounded-xl p-4 overflow-x-auto text-gray-700 dark:text-gray-300 font-mono leading-relaxed">nim,nama,angkatan,email,password
2023001,Budi Santoso,2023,budi@email.com,budi2023
2023002,Siti Rahayu,2023,siti@email.com,
2023003,Ahmad Fauzi,2023,,</pre>
        <p class="text-xs text-gray-400 mt-2">Kolom email dan password boleh dikosongkan. Jika password kosong, default = NIM mahasiswa.</p>
    </div>
</div>
@endsection
