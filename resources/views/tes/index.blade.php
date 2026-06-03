@extends('layouts.app')
@section('title', 'Tes Konsentrasi')

{{-- Pass draft data untuk resume --}}
@php $draftJson = json_encode($draft ?? []); @endphp

@push('styles')
<style>
.likert-label {
    display: flex; align-items: center; justify-content: center;
    width: 44px; height: 44px; border-radius: 10px;
    border: 1.5px solid #d0d5dd; cursor: pointer; font-weight: 700;
    font-size: 0.875rem; transition: all 0.15s; color: #667085;
    user-select: none;
}
.dark .likert-label { border-color: #374151; color: #9ca3af; }
input[type=radio]:checked + .likert-label { background: #465fff; border-color: #465fff; color: #fff; box-shadow: 0 0 0 3px rgba(70,95,255,.15); }
.likert-label:hover { border-color: #465fff; color: #465fff; background: #ecf3ff; }
.dark .likert-label:hover { background: rgba(70,95,255,.1); }
.soal-card-unanswered { border-left: 3px solid #f04438 !important; }
</style>
@endpush

@section('content')
<div x-data="{
    total: {{ $soal->count() }},
    answered: 0,
    updateProgress() {
        this.answered = document.querySelectorAll('input[type=radio]:checked').length;
    }
}" @change.window="updateProgress()">

    {{-- Progress Sticky --}}
    <div class="sticky top-16 z-40 mb-4">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-theme-xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Progress Pengisian</span>
                <span class="text-sm font-bold text-brand-500" x-text="answered + ' / ' + total + ' soal'"></span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full bg-brand-500 transition-all duration-300"
                    :style="'width:' + Math.round(answered/total*100) + '%'"></div>
            </div>
            <div class="flex justify-between mt-1">
                <span class="text-xs text-gray-400" x-text="Math.round(answered/total*100) + '%'"></span>
                <span id="save-indicator" class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><polyline points="17,21 17,13 7,13 7,21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                    Auto-save aktif
                </span>
            </div>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="rounded-2xl border border-brand-200 dark:border-brand-900 bg-brand-50 dark:bg-brand-900/20 p-5 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-500 text-white shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-gray-900 dark:text-white mb-1">Hai, <span class="text-brand-600 dark:text-brand-400">{{ $mahasiswa->nama }}</span>!</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Baca setiap pernyataan dan pilih angka yang paling sesuai dengan kondisi Anda saat ini.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['1 = Sangat Tidak Sesuai', '2 = Tidak Sesuai', '3 = Cukup Sesuai', '4 = Sesuai', '5 = Sangat Sesuai'] as $s)
                    <span class="inline-flex items-center rounded-full border border-brand-200 dark:border-brand-800 bg-white dark:bg-brand-950 px-2.5 py-1 text-xs font-medium text-brand-700 dark:text-brand-400">{{ $s }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-brand-200 dark:border-brand-800 flex items-center gap-2 text-xs text-brand-600 dark:text-brand-400">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm-1 8a1 1 0 112 0v3a1 1 0 11-2 0v-3zm1 7a1 1 0 100-2 1 1 0 000 2z" fill="currentColor"/></svg>
            Total <strong>{{ $soal->count() }} pernyataan</strong>. Tes hanya dapat dilakukan <strong>satu kali</strong>.
        </div>
    </div>

    {{-- Soal Form --}}
    <form id="form-tes" action="{{ route('tes.submit') }}" method="POST">
        @csrf
        <div class="space-y-4">
            @foreach($soal as $index => $s)
            <div id="soal-{{ $s->id }}"
                class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 transition-all duration-200 hover:shadow-theme-sm hover:border-gray-300 dark:hover:border-gray-700">
                <div class="flex items-start gap-4">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-xs font-bold shrink-0 mt-0.5">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 leading-relaxed mb-4">{{ $s->teks }}</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            @for($v = 1; $v <= 5; $v++)
                            <div>
                                <input type="radio" name="jawaban[{{ $s->id }}]" id="s{{ $s->id }}_v{{ $v }}" value="{{ $v }}" class="sr-only">
                                <label for="s{{ $s->id }}_v{{ $v }}" class="likert-label">{{ $v }}</label>
                            </div>
                            @endfor
                            <span class="ml-2 text-xs text-gray-400 hidden sm:inline">← Tidak Sesuai · · · Sangat Sesuai →</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Pastikan semua <strong>{{ $soal->count() }} pernyataan</strong> telah dijawab sebelum mengirimkan.
            </p>
            <button type="submit" id="btn-submit"
                class="bg-brand-500 hover:bg-brand-600 shadow-theme-xs inline-flex items-center gap-2 rounded-xl px-8 py-3.5 text-sm font-semibold text-white transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kirim Jawaban
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// ===== RESUME DRAFT =====
const draft = {!! $draftJson !!};
if (Object.keys(draft).length > 0) {
    Object.entries(draft).forEach(([soalId, nilai]) => {
        const radio = document.querySelector(`input[name="jawaban[${soalId}]"][value="${nilai}"]`);
        if (radio) {
            radio.checked = true;
            // Trigger Alpine progress update
            radio.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
    // Tampilkan notifikasi resume
    const notif = document.createElement('div');
    notif.className = 'fixed bottom-5 right-5 z-50 flex items-center gap-3 rounded-xl border border-brand-200 bg-brand-50 dark:bg-brand-900/30 dark:border-brand-800 px-4 py-3 shadow-theme-md text-sm text-brand-700 dark:text-brand-300';
    notif.innerHTML = `<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm4.03 7.97a.75.75 0 010 1.06l-5 5a.75.75 0 01-1.06 0l-2.5-2.5a.75.75 0 011.06-1.06l1.97 1.97 4.47-4.47a.75.75 0 011.06 0z" fill="currentColor"/></svg><span><b>Lanjut dari sebelumnya</b> — ${Object.keys(draft).length} jawaban dipulihkan.</span>`;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 4000);
}

// ===== AUTO-SAVE DRAFT (setiap 15 detik atau setelah 5 perubahan) =====
let changeCount = 0;
let saveTimer = null;
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const saveIndicator = document.getElementById('save-indicator');

function saveDraft() {
    const inputs = document.querySelectorAll('input[type=radio]:checked');
    const jawaban = {};
    inputs.forEach(i => {
        const m = i.name.match(/jawaban\[(\d+)\]/);
        if (m) jawaban[m[1]] = parseInt(i.value);
    });

    if (saveIndicator) saveIndicator.textContent = 'Menyimpan...';

    fetch('{{ route("tes.draft") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ jawaban }),
    })
    .then(r => r.json())
    .then(data => {
        if (saveIndicator && data.ok) saveIndicator.textContent = `Tersimpan (${data.saved} jawaban)`;
    })
    .catch(() => { if (saveIndicator) saveIndicator.textContent = 'Gagal simpan'; });
}

document.querySelectorAll('input[type=radio]').forEach(r => {
    r.addEventListener('change', () => {
        changeCount++;
        clearTimeout(saveTimer);
        if (changeCount >= 5) { changeCount = 0; saveDraft(); }
        else { saveTimer = setTimeout(saveDraft, 15000); }
    });
});
// Auto-save setiap 30 detik
setInterval(saveDraft, 30000);

// ===== SUBMIT VALIDATION =====
document.getElementById('form-tes').addEventListener('submit', function(e) {
    const total = {{ $soal->count() }};
    const answered = document.querySelectorAll('input[type=radio]:checked').length;
    if (answered < total) {
        e.preventDefault();
        document.querySelectorAll('[id^="soal-"]').forEach(card => card.classList.remove('soal-card-unanswered'));
        @foreach($soal as $s)
        if (!document.querySelector('input[name="jawaban[{{ $s->id }}]"]:checked')) {
            document.getElementById('soal-{{ $s->id }}').classList.add('soal-card-unanswered');
        }
        @endforeach
        const first = document.querySelector('.soal-card-unanswered');
        if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        alert('Harap jawab semua ' + total + ' pernyataan terlebih dahulu.');
        return;
    }
    // Simpan draft terakhir sebelum submit
    saveDraft();
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" stroke-dasharray="60" stroke-dashoffset="15"/></svg> Mengirim...';
});
</script>
@endpush
