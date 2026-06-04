<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SI-KONSEN</title>
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

<div class="w-full max-w-sm">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center mb-4">
            <img src="/images/logo/dark.png" alt="SI-KONSEN" class="w-20 h-20 object-contain dark:hidden">
            <img src="/images/logo/white.png" alt="SI-KONSEN" class="w-20 h-20 object-contain hidden dark:block">
        </div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Panel Admin</h1>
        <p class="text-sm text-gray-400 mt-1">SI-KONSEN — Sistem Informasi Konsentrasi</p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-theme-md p-6">

        @if($errors->any())
        <div class="mb-4 flex items-center gap-2.5 rounded-xl bg-error-50 dark:bg-error-900/20 px-4 py-3 text-sm text-error-600 dark:text-error-400">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.admin.post') }}" method="POST" class="space-y-3">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@sik.ac.id"
                    autofocus required
                    class="h-11 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-400 focus:ring-3 focus:ring-brand-500/10 focus:outline-none">
            </div>

            <div x-data="{show:false}">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" placeholder="Password"
                        required
                        class="h-11 w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 pr-11 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-400 focus:ring-3 focus:ring-brand-500/10 focus:outline-none">
                    <button type="button" @click="show=!show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg x-show="!show" class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                        <svg x-show="show" class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full h-11 rounded-xl bg-gray-900 hover:bg-gray-800 dark:bg-brand-500 dark:hover:bg-brand-600 text-white text-sm font-semibold transition-colors mt-1">
                Masuk
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
        <a href="{{ route('login.mahasiswa') }}" class="hover:text-gray-600 dark:hover:text-gray-300">← Login Mahasiswa</a>
        <span class="mx-2">·</span>
        <button @click="$store.theme.toggle()" class="hover:text-gray-600 dark:hover:text-gray-300">
            <span class="dark:hidden">🌙 Dark Mode</span>
            <span class="hidden dark:inline">☀️ Light Mode</span>
        </button>
    </p>

</div>
</body>
</html>
