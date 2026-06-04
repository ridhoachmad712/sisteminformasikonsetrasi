<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — SI-KONSEN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() { const t = localStorage.getItem('theme') || 'light'; this.theme = t; this.updateTheme(); },
                theme: 'light',
                toggle() { this.theme = this.theme === 'light' ? 'dark' : 'light'; localStorage.setItem('theme', this.theme); this.updateTheme(); },
                updateTheme() { if (this.theme === 'dark') { document.documentElement.classList.add('dark'); document.body.classList.add('dark','bg-gray-900'); } else { document.documentElement.classList.remove('dark'); document.body.classList.remove('dark','bg-gray-900'); } }
            });
        });
    </script>
    <script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark'){document.documentElement.classList.add('dark');document.body.classList.add('dark','bg-gray-900');}})();</script>
</head>
<body x-data class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-sm"
    x-data="{
        step: 'nim',
        nim: '',
        nama: '',
        angkatan: '',
        loading: false,
        error: '',
        async cekNim() {
            if (!this.nim.trim()) return;
            this.loading = true;
            this.error   = '';
            try {
                const res  = await fetch('{{ route('login.cek-nim') }}', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body:    JSON.stringify({ nim: this.nim.trim() }),
                });
                const data = await res.json();
                if (data.found) { this.nama = data.nama; this.angkatan = data.angkatan; this.step = 'konfirmasi'; }
                else { this.error = data.message; }
            } catch { this.error = 'Terjadi kesalahan, coba lagi.'; }
            this.loading = false;
        },
        balik() { this.step = 'nim'; this.nama = ''; this.error = ''; }
    }">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center mb-4">
            <img src="/images/logo/dark.png" alt="SI-KONSEN" class="w-20 h-20 object-contain dark:hidden">
            <img src="/images/logo/white.png" alt="SI-KONSEN" class="w-20 h-20 object-contain hidden dark:block">
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">SI-KONSEN</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Sistem Informasi Konsentrasi</p>
        <p class="text-xs text-gray-400 mt-0.5">Prodi Manajemen FEB UNM</p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-theme-md p-6">

        @if($errors->any())
        <div class="mb-4 flex items-center gap-2.5 rounded-xl bg-error-50 dark:bg-error-900/20 px-4 py-3 text-sm text-error-600 dark:text-error-400">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- STEP 1: Input NIM --}}
        <div x-show="step === 'nim'" x-transition:enter="transition duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Masukkan NIM Anda</p>

            <div x-show="error" x-transition class="mb-4 flex items-center gap-2.5 rounded-xl bg-error-50 dark:bg-error-900/20 px-4 py-3 text-sm text-error-600 dark:text-error-400">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
                <span x-text="error"></span>
            </div>

            <input type="text" x-model="nim" @keydown.enter="cekNim()"
                placeholder="Nomor Induk Mahasiswa" autofocus inputmode="numeric"
                class="h-11 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-400 focus:ring-3 focus:ring-brand-500/10 focus:outline-none tracking-wider mb-3">

            <button @click="cekNim()" :disabled="loading || !nim.trim()"
                class="w-full h-11 rounded-xl bg-brand-500 hover:bg-brand-600 disabled:opacity-50 text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <svg x-show="loading" class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="60" stroke-dashoffset="15"/></svg>
                <span x-text="loading ? 'Mencari...' : 'Lanjutkan'"></span>
            </button>
        </div>

        {{-- STEP 2: Konfirmasi --}}
        <div x-show="step === 'konfirmasi'" x-transition:enter="transition duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Data ditemukan</p>

            {{-- Identitas --}}
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 mb-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-brand-500 text-white font-bold text-lg shrink-0"
                    x-text="nama.charAt(0)"></div>
                <div class="min-w-0 flex-1">
                    <p class="font-bold text-gray-900 dark:text-white text-base leading-tight truncate" x-text="nama"></p>
                    <p class="text-xs text-gray-400 mt-1 space-x-2">
                        <span x-text="nim"></span>
                        <span>·</span>
                        <span x-text="'Angkatan ' + angkatan"></span>
                    </p>
                </div>
                <svg class="w-5 h-5 text-brand-400 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>

            <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-4">Apakah ini data Anda?</p>

            <div class="flex gap-3">
                {{-- Bukan saya — merah --}}
                <button @click="balik()"
                    class="flex-1 h-11 rounded-xl bg-error-50 hover:bg-error-100 dark:bg-error-500/10 dark:hover:bg-error-500/20 text-error-600 dark:text-error-400 text-sm font-semibold border border-error-200 dark:border-error-800 transition-colors flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    Bukan saya
                </button>

                {{-- Ya, masuk — hijau --}}
                <form action="{{ route('login.mahasiswa.post') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="nim" x-bind:value="nim">
                    <button type="submit"
                        class="w-full h-11 rounded-xl bg-success-500 hover:bg-success-600 text-white text-sm font-semibold transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg>
                        Ya, Masuk
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        <a href="{{ route('login.admin') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Login Admin</a>
        <span class="mx-2">·</span>
        <button @click="$store.theme.toggle()" class="inline-flex items-center gap-1.5 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="dark:hidden w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><path d="M17.45 11.97l.73.19c.085-.323-.054-.663-.34-.834-.287-.172-.652-.133-.896.095l.506.549zm-9.42-9.42l.55.51c.227-.245.266-.611.094-.897-.172-.287-.512-.425-.834-.34l.19.727zM12.92 13C9.648 13 7 10.353 7 7.085H5.5c0 4.097 3.32 7.415 7.415 7.415V13zm3.956-2.579C15.83 12.397 14.47 13 12.92 13v1.5c1.95 0 3.727-.754 5.051-1.981L16.876 10.42zm-.146.359c-.786 2.982-3.501 5.18-6.73 5.18v1.5c3.925 0 7.224-2.673 8.18-6.3l-1.45-.38zM10 18C6.157 18 3.042 14.843 3.042 11H1.542C1.542 15.671 5.33 19.5 10 19.5V18zm-6.958-7C3.042 7.772 5.24 5.056 8.222 4.271L7.84 2.82C4.215 3.776 1.542 7.075 1.542 11H3.042zm4-3.915C7 5.529 7.597 4.113 8.58 3.056L7.481 2.035C6.25 3.359 5.5 5.135 5.5 7.085H7z" fill="currentColor"/></svg>
            <svg class="hidden dark:block w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"><circle cx="10" cy="10" r="3.5" fill="currentColor"/><path d="M10 1.5v2M10 16.5v2M1.5 10h2M16.5 10h2M4.1 4.1l1.4 1.4M14.5 14.5l1.4 1.4M14.5 5.5l1.4-1.4M4.1 15.9l1.4-1.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            <span class="dark:hidden">Dark Mode</span>
            <span class="hidden dark:inline">Light Mode</span>
        </button>
    </p>

</div>
</body>
</html>
