<?php
use App\Http\Controllers\{AuthController, AdminController, GuruController, SiswaController};
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

// ── AUTH ──────────────────────────────────────────────────
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── ADMIN ─────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard',           [AdminController::class, 'dashboard'])->name('dashboard');

    // Data Jurusan > Kelas > Siswa (rekap absensi)
    Route::get('/data-jurusan',        [AdminController::class, 'dataJurusan'])->name('data-jurusan');

    // Kelola Kelas
    Route::get('/kelas',               [AdminController::class, 'kelasIndex'])->name('kelas');
    Route::post('/kelas',              [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::delete('/kelas/{id}',       [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');

    // Guru
    Route::get('/guru',                [AdminController::class, 'guruIndex'])->name('guru');
    Route::get('/guru/tambah',         [AdminController::class, 'guruCreate'])->name('guru.create');
    Route::post('/guru',               [AdminController::class, 'guruStore'])->name('guru.store');
    Route::get('/guru/{id}/edit',      [AdminController::class, 'guruEdit'])->name('guru.edit');
    Route::put('/guru/{id}',           [AdminController::class, 'guruUpdate'])->name('guru.update');
    Route::delete('/guru/{id}',        [AdminController::class, 'guruDestroy'])->name('guru.destroy');

    // Siswa
    Route::get('/siswa',               [AdminController::class, 'siswaIndex'])->name('siswa');
    Route::get('/siswa/tambah',        [AdminController::class, 'siswaCreate'])->name('siswa.create');
    Route::post('/siswa',              [AdminController::class, 'siswaStore'])->name('siswa.store');
    Route::get('/siswa/{id}/edit',     [AdminController::class, 'siswaEdit'])->name('siswa.edit');
    Route::put('/siswa/{id}',          [AdminController::class, 'siswaUpdate'])->name('siswa.update');
    Route::delete('/siswa/{id}',       [AdminController::class, 'siswaDestroy'])->name('siswa.destroy');

    // Edit/Hapus Absensi
    Route::get('/absensi/{id}/edit',   [AdminController::class, 'absensiEdit'])->name('absensi.edit');
    Route::put('/absensi/{id}',        [AdminController::class, 'absensiUpdate'])->name('absensi.update');
    Route::delete('/absensi/{id}',     [AdminController::class, 'absensiDestroy'])->name('absensi.destroy');

    // Excel (CSV)
    Route::get('/excel/template',      [AdminController::class, 'downloadTemplate'])->name('excel.template');
    Route::post('/excel/import',       [AdminController::class, 'importExcel'])->name('excel.import');
});

// ── GURU ──────────────────────────────────────────────────
Route::prefix('guru')->name('guru.')->middleware(['auth','role:guru'])->group(function () {
    Route::get('/dashboard',           [GuruController::class, 'dashboard'])->name('dashboard');
    Route::get('/data-jurusan',        [GuruController::class, 'dataJurusan'])->name('data-jurusan');
    Route::get('/input-absensi',       [GuruController::class, 'inputAbsensi'])->name('input');
    Route::post('/input-absensi',      [GuruController::class, 'simpanAbsensi'])->name('simpan');

    // Upload bukti foto absensi (dipindah ke sini, sebelumnya salah taruh di grup admin)
    Route::post('/absensi/{id_absensi}/upload-bukti', [GuruController::class, 'uploadBukti'])->name('upload-bukti');
});

// ── SISWA ─────────────────────────────────────────────────
Route::prefix('siswa')->name('siswa.')->middleware(['auth','role:siswa'])->group(function () {
    Route::get('/dashboard',           [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/riwayat',             [SiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil',              [SiswaController::class, 'profil'])->name('profil');
});

Route::get('/test-error', function () {
    return 1 / 0;
})->middleware('auth');
