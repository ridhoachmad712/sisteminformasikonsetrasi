@extends('layouts.admin')
@section('title', 'Monitor Live')
@section('page-title', 'Monitor Live Tes')

@section('content')
<div x-data="monitor()" x-init="init()" x-cloak>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-success-500"></span>
                </span>
                Sedang Mengerjakan
            </div>
            <div class="text-2xl font-bold text-success-600 dark:text-success-400" x-text="stat.aktif">—</div>
        </div>
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
            <div class="text-xs text-gray-400 mb-1">Idle (>5 menit)</div>
            <div class="text-2xl font-bold text-warning-600 dark:text-warning-400" x-text="stat.idle">—</div>
        </div>
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
            <div class="text-xs text-gray-400 mb-1">Selesai 1 Jam Terakhir</div>
            <div class="text-2xl font-bold text-brand-500" x-text="stat.selesai_1j">—</div>
        </div>
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
            <div class="text-xs text-gray-400 mb-1">Total Soal Minat / Bakat</div>
            <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $jumlahSoal['minat'] }} / {{ $jumlahSoal['bakat'] }}</div>
        </div>
    </div>

    {{-- Header + filter + refresh info --}}
    <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Peserta Tes</h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    Auto-refresh tiap 30 detik
                    <span class="ml-2 text-gray-500 dark:text-gray-300" x-show="lastUpdate">·
                        Update terakhir: <span x-text="lastUpdateText">—</span>
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <select x-model="filter.angkatan" @change="fetchData()" class="h-9 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatanList as $a)
                    <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
                <select x-model="filter.jenis" @change="fetchData()" class="h-9 rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-3 text-sm dark:bg-gray-900 dark:text-gray-300">
                    <option value="">Semua Jenis</option>
                    <option value="minat">Tes Minat</option>
                    <option value="bakat">Tes Bakat</option>
                </select>
                <button @click="fetchData()" :disabled="loading"
                    class="h-9 px-3 rounded-lg border border-gray-300 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-50 inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" :class="loading && 'animate-spin'" viewBox="0 0 24 24" fill="none"><path d="M1 4v6h6M23 20v-6h-6M20.49 9A9 9 0 005.64 5.64L1 10M23 14l-4.64 4.36A9 9 0 013.51 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Refresh
                </button>
            </div>
        </div>

        {{-- Grid Card --}}
        <div class="p-4 sm:p-5">
            {{-- Empty state --}}
            <template x-if="!loading && rows.length === 0">
                <div class="py-16 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-800 mb-3">
                        <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada peserta yang sedang/baru tes.</p>
                </div>
            </template>

            <template x-if="loading && rows.length === 0">
                <p class="py-12 text-center text-sm text-gray-400">Memuat data...</p>
            </template>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                <template x-for="row in rows" :key="row.id + '_' + row.jenis">
                    <div class="rounded-2xl border p-4 transition-all hover:shadow-theme-sm"
                        :class="row.status === 'aktif' ? 'border-success-200 dark:border-success-900 bg-success-50/30 dark:bg-success-900/10'
                              : row.status === 'idle'  ? 'border-warning-200 dark:border-warning-900 bg-warning-50/30 dark:bg-warning-900/10'
                              :                          'border-brand-200 dark:border-brand-900 bg-brand-50/30 dark:bg-brand-900/10'">

                        {{-- Header: avatar + nama + status pill --}}
                        <div class="flex items-start gap-3 mb-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl text-white text-sm font-bold shrink-0"
                                :class="row.jenis === 'minat' ? 'bg-blue-500'
                                      : row.jenis === 'bakat' ? 'bg-green-500'
                                      :                         'bg-brand-500'"
                                x-text="row.nama.charAt(0).toUpperCase()"></div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm truncate" x-text="row.nama"></p>
                                <p class="text-xs text-gray-400 truncate" x-text="row.nim + ' · ' + row.angkatan"></p>
                            </div>
                        </div>

                        {{-- Badge jenis tes + status --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold"
                                :class="row.jenis === 'minat' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400'
                                      : row.jenis === 'bakat' ? 'bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400'
                                      :                         'bg-gray-100 dark:bg-gray-800 text-gray-500'">
                                <template x-if="row.jenis === 'minat'"><span>❤ Minat</span></template>
                                <template x-if="row.jenis === 'bakat'"><span>★ Bakat</span></template>
                                <template x-if="row.jenis === 'selesai'"><span>—</span></template>
                            </span>

                            <template x-if="row.status === 'aktif'">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-success-100 dark:bg-success-500/20 px-2 py-0.5 text-xs font-medium text-success-700 dark:text-success-400">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-success-500"></span>
                                    </span>
                                    Aktif
                                </span>
                            </template>
                            <template x-if="row.status === 'idle'">
                                <span class="inline-flex items-center gap-1 rounded-full bg-warning-100 dark:bg-warning-500/20 px-2 py-0.5 text-xs font-medium text-warning-700 dark:text-warning-400">
                                    Idle
                                </span>
                            </template>
                            <template x-if="row.status === 'selesai'">
                                <span class="inline-flex items-center gap-1 rounded-full bg-brand-100 dark:bg-brand-500/20 px-2 py-0.5 text-xs font-medium text-brand-700 dark:text-brand-400">
                                    ✓ Selesai
                                </span>
                            </template>
                        </div>

                        {{-- Progress bar --}}
                        <template x-if="row.total">
                            <div>
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="text-gray-500 dark:text-gray-400">Progress</span>
                                    <span class="font-bold text-gray-700 dark:text-gray-300 tabular-nums"
                                        x-text="`${row.terjawab}/${row.total} · ${row.persen}%`"></span>
                                </div>
                                <div class="w-full bg-white dark:bg-gray-800 rounded-full h-2 overflow-hidden border border-gray-100 dark:border-gray-700">
                                    <div class="h-2 rounded-full transition-all duration-500"
                                        :class="row.jenis === 'minat' ? 'bg-blue-500' : 'bg-green-500'"
                                        :style="`width:${row.persen}%`"></div>
                                </div>
                            </div>
                        </template>
                        <template x-if="!row.total">
                            <div class="rounded-lg bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-2 text-center text-xs text-gray-400">
                                Sudah disubmit
                            </div>
                        </template>

                        {{-- Footer --}}
                        <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-700/50 flex items-center gap-1.5 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="truncate" x-text="row.last_text">—</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function monitor() {
    return {
        rows: [],
        stat: { aktif: 0, idle: 0, selesai_1j: 0 },
        loading: false,
        lastUpdate: null,
        lastUpdateText: '',
        filter: { angkatan: '', jenis: '' },

        init() {
            this.fetchData();
            // Auto refresh tiap 30 detik
            setInterval(() => this.fetchData(), 30000);
            // Update teks "X detik lalu" tiap 5 detik
            setInterval(() => this.updateLastText(), 5000);
        },

        async fetchData() {
            if (this.loading) return;
            this.loading = true;
            const params = new URLSearchParams(this.filter);
            try {
                const res = await fetch(`{{ route('admin.monitor.data') }}?${params}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                this.rows = data.rows;
                this.stat = data.stat;
                this.lastUpdate = new Date();
                this.updateLastText();
            } catch (e) {
                console.error(e);
            }
            this.loading = false;
        },

        updateLastText() {
            if (!this.lastUpdate) return;
            const diff = Math.floor((Date.now() - this.lastUpdate.getTime()) / 1000);
            if (diff < 60) this.lastUpdateText = `${diff} detik lalu`;
            else this.lastUpdateText = `${Math.floor(diff/60)} menit lalu`;
        },
    };
}
</script>
@endpush
