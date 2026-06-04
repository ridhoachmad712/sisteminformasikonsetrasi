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

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                        <th class="px-6 py-3 text-left">Mahasiswa</th>
                        <th class="px-3 py-3 text-center">Jenis Tes</th>
                        <th class="px-3 py-3 text-left">Progress</th>
                        <th class="px-3 py-3 text-center">Status</th>
                        <th class="px-3 py-3 text-right">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    <template x-for="row in rows" :key="row.id + '_' + row.jenis">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-brand-100 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 text-xs font-bold shrink-0"
                                        x-text="row.nama.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-gray-200 text-xs" x-text="row.nama"></p>
                                        <p class="text-xs text-gray-400" x-text="row.nim + ' · ' + row.angkatan"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold"
                                    :class="row.jenis === 'minat' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                          : row.jenis === 'bakat' ? 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400'
                                          : 'bg-gray-100 dark:bg-gray-800 text-gray-500'"
                                    x-text="row.jenis === 'minat' ? 'Minat' : (row.jenis === 'bakat' ? 'Bakat' : '—')"></span>
                            </td>
                            <td class="px-3 py-4">
                                <template x-if="row.total">
                                    <div class="flex items-center gap-2 min-w-[180px]">
                                        <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-1.5 rounded-full transition-all"
                                                :class="row.jenis === 'minat' ? 'bg-blue-500' : 'bg-green-500'"
                                                :style="`width:${row.persen}%`"></div>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300 tabular-nums whitespace-nowrap"
                                            x-text="`${row.terjawab}/${row.total} (${row.persen}%)`"></span>
                                    </div>
                                </template>
                                <template x-if="!row.total">
                                    <span class="text-xs text-gray-400">Sudah submit</span>
                                </template>
                            </td>
                            <td class="px-3 py-4 text-center">
                                <template x-if="row.status === 'aktif'">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success-50 dark:bg-success-500/10 px-2.5 py-1 text-xs font-medium text-success-600 dark:text-success-400">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-success-500"></span>
                                        </span>
                                        Aktif
                                    </span>
                                </template>
                                <template x-if="row.status === 'idle'">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-warning-50 dark:bg-warning-500/10 px-2.5 py-1 text-xs font-medium text-warning-700 dark:text-warning-400">
                                        Idle
                                    </span>
                                </template>
                                <template x-if="row.status === 'selesai'">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-brand-50 dark:bg-brand-500/10 px-2.5 py-1 text-xs font-medium text-brand-600 dark:text-brand-400">
                                        ✓ Selesai
                                    </span>
                                </template>
                            </td>
                            <td class="px-3 py-4 text-right text-xs text-gray-400" x-text="row.last_text">—</td>
                        </tr>
                    </template>
                    <template x-if="!loading && rows.length === 0">
                        <tr><td colspan="5" class="py-12 text-center text-sm text-gray-400">
                            Belum ada peserta yang sedang/baru tes.
                        </td></tr>
                    </template>
                    <template x-if="loading && rows.length === 0">
                        <tr><td colspan="5" class="py-12 text-center text-sm text-gray-400">Memuat data...</td></tr>
                    </template>
                </tbody>
            </table>
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
