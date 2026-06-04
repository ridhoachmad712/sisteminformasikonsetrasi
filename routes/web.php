<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\SoalController;
use App\Http\Controllers\Admin\HasilController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\DosenPaController;

Route::get('/', fn() => redirect()->route('login.mahasiswa'));

// Auth Mahasiswa
Route::get('/login', [AuthController::class, 'showLoginMahasiswa'])->name('login.mahasiswa');
// Langkah 1: cek NIM → kembalikan nama (AJAX)
Route::post('/login/cek-nim', [AuthController::class, 'cekNim'])
    ->name('login.cek-nim')
    ->middleware('throttle:login');
// Langkah 2: konfirmasi & login
Route::post('/login', [AuthController::class, 'loginMahasiswa'])
    ->name('login.mahasiswa.post')
    ->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logoutMahasiswa'])->name('logout.mahasiswa');

// Tes Mahasiswa (session guard)
Route::middleware('auth.mahasiswa')->group(function () {
    // Beranda
    Route::get('/beranda', [App\Http\Controllers\BerandaController::class, 'index'])->name('beranda');

    // Landing tes — status kedua tes
    Route::get('/tes', [TesController::class, 'index'])->name('tes.index');

    // Tes Minat
    Route::get('/tes/minat', [TesController::class, 'minat'])->name('tes.minat');
    Route::post('/tes/minat/submit', [TesController::class, 'submitMinat'])->name('tes.minat.submit');

    // Tes Bakat
    Route::get('/tes/bakat', [TesController::class, 'bakat'])->name('tes.bakat');
    Route::post('/tes/bakat/submit', [TesController::class, 'submitBakat'])->name('tes.bakat.submit');

    // Auto-save draft — rate limited
    Route::post('/tes/draft', [TesController::class, 'saveDraft'])
        ->name('tes.draft')
        ->middleware(['auth.mahasiswa', 'throttle:auto-save']);

    // Submit — rate limited (anti double-submit)
    // (throttle ditaruh di controller, tapi tambah layer di sini juga)

    // Hasil akhir (tampil setelah kedua tes selesai)
    Route::get('/tes/hasil', [TesController::class, 'hasil'])->name('tes.hasil');

    // Pilihan Konsentrasi
    Route::get('/pilihan-konsentrasi', [App\Http\Controllers\PilihanController::class, 'index'])->name('pilihan.index');
    Route::post('/pilihan-konsentrasi', [App\Http\Controllers\PilihanController::class, 'store'])->name('pilihan.store');

    // Nilai Mata Kuliah
    Route::get('/nilai', [App\Http\Controllers\NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [App\Http\Controllers\NilaiController::class, 'store'])->name('nilai.store');

    // Profil
    Route::get('/profil', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil.index');
});

// Auth Admin
Route::get('/admin/login', [AuthController::class, 'showLoginAdmin'])->name('login.admin');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('login.admin.post');
Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('logout.admin');

// Admin Panel (auth guard)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('mahasiswa', MahasiswaController::class)->parameters(['mahasiswa' => 'mahasiswum']);
    Route::post('mahasiswa/{mahasiswum}/reset-tes', [MahasiswaController::class, 'resetTes'])->name('mahasiswa.reset-tes');
    Route::get('mahasiswa-import', [\App\Http\Controllers\Admin\ImportController::class, 'showForm'])->name('mahasiswa.import.form');
    Route::post('mahasiswa-import', [\App\Http\Controllers\Admin\ImportController::class, 'import'])->name('mahasiswa.import');
    Route::get('mahasiswa-import/template', [\App\Http\Controllers\Admin\ImportController::class, 'downloadTemplate'])->name('mahasiswa.import.template');

    Route::resource('soal', SoalController::class);

    Route::get('hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('hasil/export', [HasilController::class, 'export'])->name('hasil.export');
    Route::get('hasil/{hasil}', [HasilController::class, 'show'])->name('hasil.show');

    // Jadwal Tes
    Route::resource('jadwal', JadwalController::class);
    Route::post('jadwal/{jadwal}/toggle', [JadwalController::class, 'toggleAktif'])->name('jadwal.toggle');

    // Akun Admin (akun saya)
    Route::get('akun', [\App\Http\Controllers\Admin\AkunController::class, 'index'])->name('akun');
    Route::put('akun/profil', [\App\Http\Controllers\Admin\AkunController::class, 'updateProfil'])->name('akun.profil');
    Route::put('akun/password', [\App\Http\Controllers\Admin\AkunController::class, 'updatePassword'])->name('akun.password');

    // Kelola User Admin
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except('show');

    // Dosen PA
    Route::resource('dosen-pa', DosenPaController::class)->except('show');

    // Rekap Konsentrasi
    Route::get('rekap', [\App\Http\Controllers\Admin\RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/{mahasiswum}', [\App\Http\Controllers\Admin\RekapController::class, 'show'])->name('rekap.show');

    // Monitor Live Tes
    Route::get('monitor', [\App\Http\Controllers\Admin\MonitorController::class, 'index'])->name('monitor.index');
    Route::get('monitor/data', [\App\Http\Controllers\Admin\MonitorController::class, 'data'])->name('monitor.data');
});
