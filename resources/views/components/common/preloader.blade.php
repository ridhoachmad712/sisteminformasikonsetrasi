<div x-show="!loaded"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white dark:bg-gray-900">
    <div class="flex flex-col items-center gap-3">
        <div class="h-10 w-10 animate-spin rounded-full border-4 border-brand-500 border-t-transparent"></div>
        <span class="text-sm text-gray-500 dark:text-gray-400">Memuat...</span>
    </div>
</div>
