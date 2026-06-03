<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — SINIKO</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important}</style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const saved = localStorage.getItem('theme');
                    const sys = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = saved || sys;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    if (this.theme === 'dark') {
                        document.documentElement.classList.add('dark');
                        document.body.classList.add('dark', 'bg-gray-900');
                    } else {
                        document.documentElement.classList.remove('dark');
                        document.body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,
                toggleExpanded() { this.isExpanded = !this.isExpanded; this.isMobileOpen = false; },
                toggleMobileOpen() { this.isMobileOpen = !this.isMobileOpen; },
                setMobileOpen(v) { this.isMobileOpen = v; },
                setHovered(v) { if (window.innerWidth >= 1280 && !this.isExpanded) this.isHovered = v; }
            });
        });
    </script>
    <script>
        (function() {
            const t = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (t === 'dark') { document.documentElement.classList.add('dark'); document.body.classList.add('dark','bg-gray-900'); }
        })();
    </script>
    @stack('styles')
</head>
<body x-data="{ loaded: false }"
    x-init="setTimeout(() => loaded = true, 200);
        $store.sidebar.isExpanded = window.innerWidth >= 1280;
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1280) { $store.sidebar.setMobileOpen(false); $store.sidebar.isExpanded = false; }
            else { $store.sidebar.isMobileOpen = false; $store.sidebar.isExpanded = true; }
        });">

    <x-common.preloader />

    <div class="min-h-screen xl:flex">
        {{-- Mobile backdrop --}}
        <div x-show="$store.sidebar.isMobileOpen" @click="$store.sidebar.setMobileOpen(false)"
            class="fixed inset-0 z-50 bg-gray-900/50 xl:hidden"></div>

        {{-- SIDEBAR --}}
        @php use App\Helpers\MenuHelper; $menuGroups = MenuHelper::getMenuGroups(); $currentPath = request()->path(); @endphp
        <aside id="sidebar"
            class="fixed top-0 left-0 flex flex-col h-screen px-5 bg-white dark:bg-gray-900 dark:border-gray-800 border-r border-gray-200 transition-all duration-300 ease-in-out z-[99999]"
            :class="{
                'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
                'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'translate-x-0': $store.sidebar.isMobileOpen,
                '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
            }"
            @mouseenter="$store.sidebar.setHovered(true)"
            @mouseleave="$store.sidebar.setHovered(false)">

            {{-- Logo --}}
            <div class="pt-8 pb-7 flex"
                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="/images/logo/dark.png" alt="SINIKO" class="w-9 h-9 object-contain shrink-0 dark:hidden">
                    <img src="/images/logo/white.png" alt="SINIKO" class="w-9 h-9 object-contain shrink-0 hidden dark:block">
                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                        class="text-gray-900 dark:text-white font-bold text-base leading-tight">
                        SINIKO<br><span class="font-normal text-gray-500 text-xs">Konsentrasi</span>
                    </span>
                </a>
            </div>

            {{-- Nav --}}
            <div class="flex flex-col overflow-y-auto no-scrollbar">
                <nav>
                    @foreach($menuGroups as $groupIndex => $group)
                    <div class="mb-6">
                        <h2 class="mb-3 text-xs uppercase leading-5 text-gray-400 flex"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
                            <template x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $group['title'] }}</span>
                            </template>
                            <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                <span class="text-gray-400">···</span>
                            </template>
                        </h2>
                        <ul class="flex flex-col gap-1">
                            @foreach($group['items'] as $item)
                            <li>
                                <a href="{{ $item['path'] }}" class="menu-item group"
                                    :class="[
                                        '{{ MenuHelper::isActive($item['path']) }}' === '1' ? 'menu-item-active' : 'menu-item-inactive',
                                        (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'
                                    ]">
                                    <span :class="'{{ MenuHelper::isActive($item['path']) }}' === '1' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                        {!! MenuHelper::getIconSvg($item['icon']) !!}
                                    </span>
                                    <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                        class="menu-item-text">{{ $item['name'] }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </nav>

                {{-- Logout --}}
                <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <form action="{{ route('logout.admin') }}" method="POST">
                        @csrf
                        <button type="submit" class="menu-item menu-item-inactive w-full text-left">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none"><path d="M15 3H7C5.895 3 5 3.895 5 5v14c0 1.105.895 2 2 2h8M19 12H9M16 9l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered
            }">

            {{-- HEADER --}}
            <header class="sticky top-0 flex w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 z-[99999]">
                <div class="flex items-center justify-between w-full px-4 py-3 xl:px-6">
                    <div class="flex items-center gap-3">
                        <button @click="$store.sidebar.toggleExpanded()"
                            class="hidden xl:flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 dark:border-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
                            <svg width="16" height="12" viewBox="0 0 16 12" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.583 1C0.583.586.919.25 1.333.25H14.667c.414 0 .75.336.75.75s-.336.75-.75.75H1.333C.919 1.75.583 1.414.583 1zm0 10c0-.414.336-.75.75-.75H14.667c.414 0 .75.336.75.75s-.336.75-.75.75H1.333C.919 11.75.583 11.414.583 11zM1.333 5.25C.919 5.25.583 5.586.583 6s.336.75.75.75H8c.414 0 .75-.336.75-.75s-.336-.75-.75-.75H1.333z" fill="currentColor"/></svg>
                        </button>
                        <button @click="$store.sidebar.toggleMobileOpen()"
                            class="flex xl:hidden items-center justify-center w-10 h-10 text-gray-500 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </button>
                        <div>
                            <h1 class="text-sm font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-xs text-gray-400">SINIKO — Sistem Informasi Konsentrasi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="$store.theme.toggle()"
                            class="flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 dark:border-gray-800 rounded-full hover:bg-gray-100 dark:hover:bg-white/5">
                            <svg class="dark:hidden" width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M17.45 11.97l.73.19c.085-.323-.054-.663-.34-.834-.287-.172-.652-.133-.896.095l.506.549zm-9.42-9.42l.55.51c.227-.245.266-.611.094-.897-.172-.287-.512-.425-.834-.34l.19.727zM12.92 13C9.648 13 7 10.353 7 7.085H5.5c0 4.097 3.32 7.415 7.415 7.415V13zm3.956-2.579C15.83 12.397 14.47 13 12.92 13v1.5c1.95 0 3.727-.754 5.051-1.981L16.876 10.42zm-.146.359c-.786 2.982-3.501 5.18-6.73 5.18v1.5c3.925 0 7.224-2.673 8.18-6.3l-1.45-.38zM10 18C6.157 18 3.042 14.843 3.042 11H1.542C1.542 15.671 5.33 19.5 10 19.5V18zm-6.958-7C3.042 7.772 5.24 5.056 8.222 4.271L7.84 2.82C4.215 3.776 1.542 7.075 1.542 11H3.042zm4-3.915C7 5.529 7.597 4.113 8.58 3.056L7.481 2.035C6.25 3.359 5.5 5.135 5.5 7.085H7z" fill="currentColor"/></svg>
                            <svg class="hidden dark:block" width="18" height="18" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 1.542a.75.75 0 01.75.75v1.25a.75.75 0 01-1.5 0V2.292a.75.75 0 01.75-.75zm0 5.25a3.208 3.208 0 100 6.416A3.208 3.208 0 0010 6.793zm5.98-1.018a.75.75 0 00-1.06-1.06l-.884.884a.75.75 0 001.06 1.06l.884-.884zM17.708 10a.75.75 0 01-.75.75h-1.25a.75.75 0 010-1.5h1.25a.75.75 0 01.75.75zm-2.69 4.22a.75.75 0 001.06-1.06l-.883-.884a.75.75 0 00-1.06 1.06l.883.884zM10 15.458a.75.75 0 01.75.75v1.25a.75.75 0 01-1.5 0v-1.25a.75.75 0 01.75-.75zm-4.22-1.238a.75.75 0 001.06-1.06l-.883-.884a.75.75 0 10-1.06 1.06l.883.884zM4.292 10a.75.75 0 01-.75.75H2.292a.75.75 0 010-1.5h1.25a.75.75 0 01.75.75zm.547-5.238a.75.75 0 10-1.06 1.06l.883.884a.75.75 0 001.06-1.06l-.883-.884z" fill="currentColor"/></svg>
                        </button>
                        <div class="relative pl-3 border-l border-gray-200 dark:border-gray-800" x-data="{open:false}">
                            <button @click="open = !open" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                <div class="flex items-center justify-center w-9 h-9 rounded-full bg-brand-500 text-white text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-400">Administrator</p>
                                </div>
                                <svg class="hidden sm:block w-4 h-4 text-gray-400 transition-transform" :class="open && 'rotate-180'" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>

                            {{-- Dropdown --}}
                            <div x-show="open" x-cloak @click.outside="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-theme-lg py-1.5 z-[99999]">
                                <a href="{{ route('admin.akun') }}"
                                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5">
                                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Akun &amp; Password
                                </a>
                                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                <form action="{{ route('logout.admin') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-error-600 dark:text-error-400 hover:bg-error-50 dark:hover:bg-error-500/10">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M15 3H7C5.895 3 5 3.895 5 5v14c0 1.105.895 2 2 2h8M19 12H9M16 9l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- CONTENT --}}
            <main class="p-4 md:p-6 max-w-screen-2xl mx-auto">
                @if(session('success'))
                    <div x-data="{show:true}" x-show="show" class="mb-4 flex items-center gap-3 rounded-xl border border-success-200 bg-success-50 px-4 py-3 dark:border-success-900 dark:bg-success-900/20">
                        <svg class="shrink-0 text-success-600 dark:text-success-400 size-5" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg>
                        <p class="text-sm text-success-700 dark:text-success-400">{{ session('success') }}</p>
                        <button @click="show=false" class="ml-auto text-success-500"><svg class="size-4" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                    </div>
                @endif
                @if(session('error') || $errors->any())
                    <div x-data="{show:true}" x-show="show" class="mb-4 flex items-center gap-3 rounded-xl border border-error-200 bg-error-50 px-4 py-3 dark:border-error-900 dark:bg-error-900/20">
                        <svg class="shrink-0 text-error-600 dark:text-error-400 size-5" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
                        <p class="text-sm text-error-700 dark:text-error-400">{{ session('error') ?? $errors->first() }}</p>
                        <button @click="show=false" class="ml-auto text-error-500"><svg class="size-4" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
