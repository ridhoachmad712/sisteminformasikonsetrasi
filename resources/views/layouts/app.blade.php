<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tes Konsentrasi') — SINIKO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const t = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                    this.theme = t; this.updateTheme();
                },
                theme: 'light',
                toggle() { this.theme = this.theme === 'light' ? 'dark' : 'light'; localStorage.setItem('theme', this.theme); this.updateTheme(); },
                updateTheme() {
                    if (this.theme === 'dark') { document.documentElement.classList.add('dark'); document.body.classList.add('dark','bg-gray-900'); }
                    else { document.documentElement.classList.remove('dark'); document.body.classList.remove('dark','bg-gray-900'); }
                }
            });
        });
    </script>
    <script>(function(){const t=localStorage.getItem('theme')||(window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');if(t==='dark'){document.documentElement.classList.add('dark');document.body.classList.add('dark','bg-gray-900');}})();</script>
    @stack('styles')
</head>
<body x-data="{loaded:false}" x-init="setTimeout(()=>loaded=true,200)" class="bg-gray-50 dark:bg-gray-900">
    <x-common.preloader />

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-[9999] bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-2xl mx-auto px-4 h-14 flex items-center justify-between gap-3">

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex items-center gap-2 shrink-0">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-brand-500 shrink-0">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <span class="font-bold text-gray-900 dark:text-white text-sm leading-none">SINIKO<br><span class="font-normal text-gray-400 text-xs">Konsentrasi</span></span>
            </a>

            {{-- Right actions --}}
            @if(session('mahasiswa_id'))
            <div class="flex items-center gap-2 ml-auto">
                {{-- Theme toggle --}}
                <button @click="$store.theme.toggle()"
                    class="flex items-center justify-center w-9 h-9 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                    <svg class="dark:hidden w-4 h-4" viewBox="0 0 20 20" fill="none"><path d="M17.45 11.97l.73.19c.085-.323-.054-.663-.34-.834-.287-.172-.652-.133-.896.095l.506.549zm-9.42-9.42l.55.51c.227-.245.266-.611.094-.897-.172-.287-.512-.425-.834-.34l.19.727zM12.92 13C9.648 13 7 10.353 7 7.085H5.5c0 4.097 3.32 7.415 7.415 7.415V13zm3.956-2.579C15.83 12.397 14.47 13 12.92 13v1.5c1.95 0 3.727-.754 5.051-1.981L16.876 10.42zm-.146.359c-.786 2.982-3.501 5.18-6.73 5.18v1.5c3.925 0 7.224-2.673 8.18-6.3l-1.45-.38zM10 18C6.157 18 3.042 14.843 3.042 11H1.542C1.542 15.671 5.33 19.5 10 19.5V18zm-6.958-7C3.042 7.772 5.24 5.056 8.222 4.271L7.84 2.82C4.215 3.776 1.542 7.075 1.542 11H3.042zm4-3.915C7 5.529 7.597 4.113 8.58 3.056L7.481 2.035C6.25 3.359 5.5 5.135 5.5 7.085H7z" fill="currentColor"/></svg>
                    <svg class="hidden dark:block w-4 h-4" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="3.5" fill="currentColor"/><path d="M10 1.5v2M10 16.5v2M1.5 10h2M16.5 10h2M4.1 4.1l1.4 1.4M14.5 14.5l1.4 1.4M14.5 5.5l1.4-1.4M4.1 15.9l1.4-1.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>

                {{-- Avatar + nama --}}
                <a href="{{ route('profil.index') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 text-xs font-bold shrink-0">
                        {{ strtoupper(substr(session('mahasiswa_nama','M'), 0, 1)) }}
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300 max-w-[120px] truncate">
                        {{ session('mahasiswa_nama') }}
                    </span>
                </a>

                {{-- Logout --}}
                <form action="{{ route('logout.mahasiswa') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center justify-center w-9 h-9 rounded-full text-gray-400 hover:text-error-500 hover:bg-error-50 dark:hover:bg-error-500/10 transition-colors"
                        title="Keluar">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M15 3H7C5.895 3 5 3.895 5 5v14c0 1.105.895 2 2 2h8M19 12H9M16 9l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
            @else
            <button @click="$store.theme.toggle()"
                class="ml-auto flex items-center justify-center w-9 h-9 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                <svg class="dark:hidden w-4 h-4" viewBox="0 0 20 20" fill="none"><path d="M17.45 11.97l.73.19c.085-.323-.054-.663-.34-.834-.287-.172-.652-.133-.896.095l.506.549zm-9.42-9.42l.55.51c.227-.245.266-.611.094-.897-.172-.287-.512-.425-.834-.34l.19.727zM12.92 13C9.648 13 7 10.353 7 7.085H5.5c0 4.097 3.32 7.415 7.415 7.415V13zm3.956-2.579C15.83 12.397 14.47 13 12.92 13v1.5c1.95 0 3.727-.754 5.051-1.981L16.876 10.42zm-.146.359c-.786 2.982-3.501 5.18-6.73 5.18v1.5c3.925 0 7.224-2.673 8.18-6.3l-1.45-.38zM10 18C6.157 18 3.042 14.843 3.042 11H1.542C1.542 15.671 5.33 19.5 10 19.5V18zm-6.958-7C3.042 7.772 5.24 5.056 8.222 4.271L7.84 2.82C4.215 3.776 1.542 7.075 1.542 11H3.042zm4-3.915C7 5.529 7.597 4.113 8.58 3.056L7.481 2.035C6.25 3.359 5.5 5.135 5.5 7.085H7z" fill="currentColor"/></svg>
                <svg class="hidden dark:block w-4 h-4" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="3.5" fill="currentColor"/><path d="M10 1.5v2M10 16.5v2M1.5 10h2M16.5 10h2M4.1 4.1l1.4 1.4M14.5 14.5l1.4 1.4M14.5 5.5l1.4-1.4M4.1 15.9l1.4-1.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            </button>
            @endif
        </div>
    </header>

    {{-- BOTTOM NAV — mobile only (disembunyikan saat mengerjakan tes) --}}
    @if(session('mahasiswa_id') && !View::hasSection('hide-bottom-nav'))
    <nav class="fixed bottom-0 left-0 right-0 z-[9998] bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 sm:hidden safe-bottom">
        <div class="grid grid-cols-3 h-16">
            @php
                $currentRoute = Route::currentRouteName();
                $navItems = [
                    ['route' => 'beranda',     'label' => 'Beranda', 'icon' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                    ['route' => 'tes.index',   'label' => 'Tes',     'icon' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'],
                    ['route' => 'profil.index','label' => 'Profil',  'icon' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.306 3.5 3.5 7.306 3.5 12c0 2.153.8 4.118 2.119 5.616.553-2.306 2.629-4.021 5.105-4.021h2.55c2.476 0 4.552 1.715 5.105 4.021C19.699 16.118 20.5 14.153 20.5 12c0-4.694-3.806-8.5-8.5-8.5zM12 12.786c1.114 0 2.017-.903 2.017-2.018 0-1.114-.903-2.017-2.017-2.017-1.115 0-2.018.903-2.018 2.017 0 1.115.903 2.018 2.018 2.018z" fill="currentColor"/>'],
                ];
            @endphp
            @foreach($navItems as $item)
            @php $active = str_starts_with($currentRoute ?? '', explode('.', $item['route'])[0]); @endphp
            <a href="{{ route($item['route']) }}"
                class="flex flex-col items-center justify-center gap-1 text-xs transition-colors
                    {{ $active ? 'text-brand-500' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">{!! $item['icon'] !!}</svg>
                <span class="text-[10px] font-medium">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </div>
    </nav>
    @endif

    {{-- CONTENT --}}
    <main class="max-w-2xl mx-auto px-4 py-5 sm:pb-6 {{ (session('mahasiswa_id') && !View::hasSection('hide-bottom-nav')) ? 'pb-24' : 'pb-6' }}">

        @if(session('success'))
        <div x-data="{show:true}" x-show="show"
            class="mb-4 flex items-center gap-3 rounded-xl border border-success-200 bg-success-50 dark:bg-success-900/20 dark:border-success-900 px-4 py-3">
            <svg class="shrink-0 text-success-600 dark:text-success-400 w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg>
            <p class="text-sm text-success-700 dark:text-success-400 flex-1">{{ session('success') }}</p>
            <button @click="show=false" class="shrink-0 text-success-400 hover:text-success-600">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div x-data="{show:true}" x-show="show"
            class="mb-4 flex items-center gap-3 rounded-xl border border-error-200 bg-error-50 dark:bg-error-900/20 dark:border-error-900 px-4 py-3">
            <svg class="shrink-0 text-error-600 dark:text-error-400 w-4 h-4" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
            <p class="text-sm text-error-700 dark:text-error-400 flex-1">{{ $errors->first() }}</p>
            <button @click="show=false" class="shrink-0 text-error-400 hover:text-error-600">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
