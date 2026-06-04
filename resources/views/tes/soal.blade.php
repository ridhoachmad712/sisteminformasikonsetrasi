@extends('layouts.app')
@section('title', $jenis === 'minat' ? 'Tes Minat' : 'Tes Bakat')

{{-- Sembunyikan bottom nav mobile selama mengerjakan tes --}}
@section('hide-bottom-nav', true)

@php
$judul       = $jenis === 'minat' ? 'Tes Minat' : 'Tes Bakat';
$routeSubmit = $jenis === 'minat' ? 'tes.minat.submit' : 'tes.bakat.submit';
$iconColor   = $jenis === 'minat' ? '#465fff' : '#12b76a';
$draftJson   = json_encode($draft ?? []);
@endphp

@push('styles')
<style>
/* Tombol Likert — touch-friendly di mobile */
.likert-wrap { display: flex; gap: 8px; }
.likert-label {
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    flex: 1; min-width: 0; aspect-ratio: 1;
    max-width: 56px; border-radius: 12px;
    border: 1.5px solid #d0d5dd; cursor: pointer; font-weight: 700;
    font-size: .875rem; transition: all .15s; color: #667085; user-select: none;
    -webkit-tap-highlight-color: transparent;
}
.dark .likert-label { border-color: #374151; color: #9ca3af; }
input[type=radio]:checked + .likert-label {
    color: #fff;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 25%, transparent);
    background: var(--accent); border-color: var(--accent);
}
.likert-label:hover { border-color: var(--accent); color: var(--accent); background: color-mix(in srgb, var(--accent) 8%, transparent); }
.likert-label .likert-sub { font-size: 9px; font-weight: 500; opacity: .65; line-height: 1; margin-top: 2px; }
.soal-card-unanswered { border-left: 3px solid #f04438 !important; }
/* Safe area untuk iPhone */
.safe-bottom { padding-bottom: env(safe-area-inset-bottom, 0); }
[x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="tesData()" x-init="initTimer()"
    @change.window="updateProgress()"
    style="--accent: {{ $iconColor }}">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-3">
        <a href="{{ route('tes.index') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Tes</a>
        <svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <span class="font-medium truncate" style="color:{{ $iconColor }}">{{ $judul }}</span>
    </div>

    {{-- Progress + Countdown sticky --}}
    <div class="sticky top-14 z-40 mb-3 -mx-4 px-4 pt-2 pb-2 bg-gray-50 dark:bg-gray-900">
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-3 shadow-theme-xs">

            {{-- Baris atas: judul + countdown --}}
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $iconColor }}"></span>
                    {{ $judul }}
                </span>

                @if($jadwal)
                {{-- Countdown timer --}}
                <span class="flex items-center gap-1.5 text-xs font-bold tabular-nums rounded-lg px-2.5 py-1 transition-colors"
                    :class="timeLow ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400 animate-pulse' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300'">
                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span x-text="timeText">--:--</span>
                </span>
                @endif
            </div>

            {{-- Baris bawah: progress soal --}}
            <div class="flex items-center gap-2">
                <div class="flex-1 bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full transition-all duration-300" style="background:{{ $iconColor }}"
                        :style="'width:' + Math.round(answered/total*100) + '%'"></div>
                </div>
                <span class="text-xs font-bold tabular-nums shrink-0" style="color:{{ $iconColor }}"
                    x-text="answered + '/' + total"></span>
            </div>
        </div>
    </div>

    {{-- Info singkat --}}
    <div class="rounded-xl border px-4 py-3 mb-4 flex items-center gap-3"
        style="border-color:{{ $iconColor }}30; background:{{ $iconColor }}08">
        <div class="flex items-center justify-center w-8 h-8 rounded-lg shrink-0 text-white" style="background:{{ $iconColor }}">
            @if($jenis === 'minat')
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            @else
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            @endif
        </div>
        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
            @if($jenis === 'minat')
            Pilih angka yang menggambarkan <strong>ketertarikan</strong> Anda. <span class="text-gray-400">1 = Sangat Tidak Sesuai, 5 = Sangat Sesuai.</span>
            @else
            Pilih angka yang menggambarkan <strong>kemampuan</strong> Anda saat ini. <span class="text-gray-400">1 = Sangat Tidak Sesuai, 5 = Sangat Sesuai.</span>
            @endif
        </p>
    </div>

    {{-- Soal --}}
    <form id="form-tes" action="{{ route($routeSubmit) }}" method="POST" @submit="onSubmit($event)">
        @csrf
        <input type="hidden" name="_jenis" value="{{ $jenis }}">
        <div class="space-y-3">
            @foreach($soal as $index => $s)
            <div id="soal-{{ $s->id }}"
                class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 transition-all">
                {{-- Nomor + teks --}}
                <div class="flex items-start gap-3 mb-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-md text-xs font-bold shrink-0 mt-0.5"
                        style="background:{{ $iconColor }}18; color:{{ $iconColor }}">{{ $index + 1 }}</span>
                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">{{ $s->teks }}</p>
                </div>

                {{-- Likert scale — full width di mobile --}}
                <div class="likert-wrap">
                    @for($v = 1; $v <= 5; $v++)
                    @php
                        $labels = ['','STS','TS','CS','S','SS']; // 1-5
                    @endphp
                    <input type="radio" name="jawaban[{{ $s->id }}]"
                        id="s{{ $s->id }}_v{{ $v }}" value="{{ $v }}" class="sr-only">
                    <label for="s{{ $s->id }}_v{{ $v }}" class="likert-label">
                        {{ $v }}
                        <span class="likert-sub">{{ $labels[$v] }}</span>
                    </label>
                    @endfor
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tombol buka review --}}
        <div class="mt-6 mb-2">
            <button type="button" @click="openReview()"
                class="w-full h-12 rounded-xl text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-theme-xs"
                style="background:{{ $iconColor }}">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Periksa &amp; Kirim {{ $judul }}
            </button>
            <p class="text-center text-xs text-gray-400 mt-2">Anda bisa memeriksa jawaban sebelum mengirim.</p>
        </div>

        {{-- Tombol submit asli (tersembunyi) — dipicu dari modal / saat waktu habis --}}
        <button type="submit" id="btn-submit" class="hidden"></button>
    </form>

    {{-- ══ MODAL REVIEW ══════════════════════════════════════ --}}
    <div x-show="showReview" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-[99999] flex items-end sm:items-center justify-center bg-gray-900/60 backdrop-blur-sm p-0 sm:p-4"
        @click.self="showReview = false">

        <div class="bg-white dark:bg-gray-800 w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-theme-lg max-h-[85vh] flex flex-col"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-full sm:translate-y-4 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100">

            {{-- Header --}}
            <div class="px-5 pt-5 pb-3 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white">Periksa Jawaban</h3>
                    <button type="button" @click="showReview = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
                {{-- Ringkasan --}}
                <div class="mt-3 flex items-center gap-3">
                    <div class="flex-1">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-500 dark:text-gray-400">Terjawab</span>
                            <span class="font-bold" style="color:{{ $iconColor }}" x-text="answered + ' / ' + total"></span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                            <div class="h-1.5 rounded-full transition-all" style="background:{{ $iconColor }}"
                                :style="'width:' + Math.round(answered/total*100) + '%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigator nomor soal --}}
            <div class="px-5 py-4 overflow-y-auto">
                <p class="text-xs text-gray-400 mb-3">
                    <span x-show="answered === total" style="color:#12b76a">✓ Semua pernyataan sudah dijawab.</span>
                    <span x-show="answered < total">Ketuk nomor untuk melompat ke pernyataan yang belum dijawab (ditandai merah).</span>
                </p>
                <div class="grid grid-cols-6 sm:grid-cols-8 gap-2">
                    <template x-for="item in soalList" :key="item.id">
                        <button type="button" @click="jumpTo(item.id)"
                            class="aspect-square rounded-lg text-xs font-bold flex items-center justify-center border transition-colors"
                            :class="isAnswered(item.id)
                                ? 'text-white border-transparent bg-success-500'
                                : 'bg-error-50 dark:bg-error-500/10 text-error-600 dark:text-error-400 border-error-200 dark:border-error-800'"
                            x-text="item.no"></button>
                    </template>
                </div>
            </div>

            {{-- Footer aksi --}}
            <div class="px-5 pt-4 border-t border-gray-100 dark:border-gray-700"
                style="padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 1.5rem)">
                <template x-if="answered < total">
                    <div class="flex items-center justify-center gap-1.5 mb-4 text-xs text-error-500">
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Masih ada <span x-text="total - answered" class="font-bold"></span> pernyataan belum dijawab</span>
                    </div>
                </template>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="showReview = false"
                        class="h-12 rounded-xl border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        Periksa Lagi
                    </button>
                    <button type="button" @click="confirmSubmit()"
                        :disabled="answered < total || submitting"
                        class="h-12 rounded-xl text-white text-sm font-semibold transition-colors flex items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed"
                        style="background:{{ $iconColor }}">
                        <template x-if="!submitting">
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </template>
                        <template x-if="submitting">
                            <svg class="w-4 h-4 shrink-0 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="60" stroke-dashoffset="15"/></svg>
                        </template>
                        <span x-text="submitting ? 'Mengirim…' : 'Kirim'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const CSRF        = document.querySelector('meta[name=csrf-token]').content;
const DRAFT_DATA  = {!! $draftJson !!};
const JENIS       = '{{ $jenis }}';
const TOTAL_SOAL  = {{ $soal->count() }};
const SOAL_LIST   = [
    @foreach($soal as $index => $s)
    { id: {{ $s->id }}, no: {{ $index + 1 }} },
    @endforeach
];
@if($jadwal)
const END_TIME    = new Date('{{ $jadwal->tanggal_selesai->toIso8601String() }}').getTime();
@else
const END_TIME    = null;
@endif

function tesData() {
    return {
        total: TOTAL_SOAL,
        soalList: SOAL_LIST,
        answered: 0,
        answeredIds: new Set(),
        showReview: false,
        submitting: false,
        timeText: '--:--',
        timeLow: false,        // true jika sisa < 5 menit
        habis: false,
        isDirty: false,
        isSaving: false,
        saveTimer: null,

        updateProgress() {
            const checked = document.querySelectorAll('input[type=radio]:checked');
            this.answered = checked.length;
            this.answeredIds = new Set(
                Array.from(checked).map(r => {
                    const m = r.name.match(/jawaban\[(\d+)\]/);
                    return m ? parseInt(m[1]) : null;
                }).filter(Boolean)
            );
        },

        // ── Review ─────────────────────────────────
        isAnswered(soalId) {
            return this.answeredIds.has(soalId);
        },
        openReview() {
            this.updateProgress();
            this.showReview = true;
        },
        jumpTo(soalId) {
            this.showReview = false;
            this.$nextTick(() => {
                const el = document.getElementById('soal-' + soalId);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (!this.isAnswered(soalId)) el.classList.add('soal-card-unanswered');
                }
            });
        },
        confirmSubmit() {
            if (this.answered < this.total) {
                this.toast(`Masih ada ${this.total - this.answered} pernyataan yang belum dijawab`, 'error');
                return;
            }
            if (this.submitting) return;
            this.submitting = true;
            this.saveDraft(true);
            const form = document.getElementById('form-tes');
            form.requestSubmit ? form.requestSubmit() : form.submit();
        },

        // ── Timer ──────────────────────────────────
        initTimer() {
            // restore draft
            this.restoreDraft();
            // listener autosave
            document.querySelectorAll('input[type=radio]').forEach(r => {
                r.addEventListener('change', () => {
                    this.isDirty = true;
                    clearTimeout(this.saveTimer);
                    this.saveTimer = setTimeout(() => this.saveDraft(), 60000);
                });
            });
            setInterval(() => this.saveDraft(), 90000);
            document.addEventListener('visibilitychange', () => { if (document.visibilityState === 'hidden') this.saveDraft(true); });
            window.addEventListener('beforeunload', () => this.saveDraft(true));

            this.updateProgress();

            // countdown
            if (END_TIME) {
                this.tickTimer();
                setInterval(() => this.tickTimer(), 1000);
            }
        },

        tickTimer() {
            const diff = END_TIME - Date.now();
            if (diff <= 0) {
                this.timeText = '00:00';
                if (!this.habis) { this.habis = true; this.waktuHabis(); }
                return;
            }
            this.timeLow = diff < 5 * 60 * 1000; // < 5 menit
            const totalSec = Math.floor(diff / 1000);
            const h = Math.floor(totalSec / 3600);
            const m = Math.floor((totalSec % 3600) / 60);
            const s = totalSec % 60;
            const pad = n => String(n).padStart(2, '0');
            this.timeText = h > 0 ? `${pad(h)}:${pad(m)}:${pad(s)}` : `${pad(m)}:${pad(s)}`;
        },

        waktuHabis() {
            // Simpan jawaban terakhir lalu paksa kirim form
            this.saveDraft(true);
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900/70 backdrop-blur-sm p-6';
            overlay.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-xs w-full text-center shadow-theme-lg">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-error-50 dark:bg-error-500/15 mb-4">
                        <svg class="w-7 h-7 text-error-500" viewBox="0 0 24 24" fill="none"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-1">Waktu Habis</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Waktu pengerjaan telah berakhir. Jawaban Anda sedang dikirim...</p>
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 animate-spin text-error-500" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="60" stroke-dashoffset="15"/></svg>
                    </div>
                </div>`;
            document.body.appendChild(overlay);
            // Submit apa adanya (server akan menolak jika benar2 lewat, lalu redirect)
            setTimeout(() => {
                const form = document.getElementById('form-tes');
                form.dataset.timeup = '1';
                form.submit();
            }, 1500);
        },

        // ── Draft ──────────────────────────────────
        restoreDraft() {
            if (Object.keys(DRAFT_DATA).length === 0) return;
            Object.entries(DRAFT_DATA).forEach(([soalId, nilai]) => {
                const r = document.querySelector(`input[name="jawaban[${soalId}]"][value="${nilai}"]`);
                if (r) { r.checked = true; }
            });
            this.updateProgress();
            this.toast(`${Object.keys(DRAFT_DATA).length} jawaban dipulihkan`, 'info');
        },

        saveDraft(force = false) {
            if ((!this.isDirty && !force) || this.isSaving) return;
            this.isSaving = true; this.isDirty = false;
            const jawaban = {};
            document.querySelectorAll('input[type=radio]:checked').forEach(i => {
                const m = i.name.match(/jawaban\[(\d+)\]/);
                if (m) jawaban[m[1]] = parseInt(i.value);
            });
            fetch('{{ route("tes.draft") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ jawaban, jenis: JENIS }),
            }).then(r => r.json()).then(() => { this.isSaving = false; }).catch(() => { this.isSaving = false; });
        },

        // ── Submit ─────────────────────────────────
        onSubmit(e) {
            // Jika dipaksa karena waktu habis, lewati validasi kelengkapan
            const form = e.target;
            if (form.dataset.timeup === '1') return;

            const answered = document.querySelectorAll('input[type=radio]:checked').length;
            if (answered < this.total) {
                e.preventDefault();
                document.querySelectorAll('[id^="soal-"]').forEach(c => c.classList.remove('soal-card-unanswered'));
                @foreach($soal as $s)
                if (!document.querySelector('input[name="jawaban[{{ $s->id }}]"]:checked'))
                    document.getElementById('soal-{{ $s->id }}').classList.add('soal-card-unanswered');
                @endforeach
                const first = document.querySelector('.soal-card-unanswered');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                this.toast(`Masih ada ${this.total - answered} pernyataan yang belum dijawab`, 'error');
                return;
            }
            this.saveDraft(true);
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;
            btn.innerHTML = `<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="60" stroke-dashoffset="15"/></svg> Mengirim...`;
        },

        // ── Toast helper ───────────────────────────
        toast(msg, type = 'info') {
            const colors = type === 'error'
                ? 'bg-error-500 text-white'
                : 'bg-white dark:bg-gray-800 border border-brand-200 dark:border-brand-800 text-brand-700 dark:text-brand-300';
            const el = document.createElement('div');
            el.className = `fixed bottom-20 sm:bottom-5 left-4 right-4 sm:left-auto sm:right-5 sm:w-80 z-50 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium shadow-theme-md ${colors}`;
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 4000);
        },
    };
}
</script>
@endpush
