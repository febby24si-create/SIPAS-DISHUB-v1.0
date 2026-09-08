<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\KlasifikasiSuratController;
use App\Http\Controllers\TemplateSuratController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\PencarianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NomorSuratController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Master Data
    Route::resource('jenis-surat', JenisSuratController::class);
    Route::resource('klasifikasi-surat', KlasifikasiSuratController::class)->except(['show']);
    Route::resource('template-surat', TemplateSuratController::class);
    Route::get('nomor-surat', [NomorSuratController::class, 'index'])->name('nomor-surat.index');
    Route::resource('unit-kerja', UnitKerjaController::class)->except(['show']);
    Route::resource('pegawai', PegawaiController::class);

    // Persuratan - Buat Surat
    // Alur: Pilih Jenis Surat -> Pilih Template -> Isi Form Dinamis -> Preview -> Generate -> Finalisasi -> Arsip
    Route::get('buat-surat', [SuratController::class, 'create'])->name('buat-surat.create');
    Route::get('buat-surat/template/{jenisSurat}', [SuratController::class, 'pilihTemplate'])->name('buat-surat.pilih-template');
    Route::get('buat-surat/form/{template}', [SuratController::class, 'form'])->name('buat-surat.form');
    Route::post('buat-surat', [SuratController::class, 'store'])->name('buat-surat.store');
    Route::get('buat-surat/{surat}', [SuratController::class, 'show'])->name('buat-surat.show');
    Route::patch('buat-surat/{surat}/finalize', [SuratController::class, 'finalize'])->name('buat-surat.finalize');

    // Persuratan - Surat Masuk
    Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('surat-masuk/input', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('surat-masuk/{surat}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('surat-masuk/{surat}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::put('surat-masuk/{surat}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::delete('surat-masuk/{surat}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
    Route::get('surat-masuk/{surat}/download', [SuratMasukController::class, 'download'])->name('surat-masuk.download');

    // Persuratan - Surat Keluar
    Route::get('surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
    Route::get('surat-keluar/input', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
    Route::post('surat-keluar', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
    Route::get('surat-keluar/draft', [SuratKeluarController::class, 'draft'])->name('surat-keluar.draft');
    Route::get('surat-keluar/{surat}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
    Route::get('surat-keluar/{surat}/edit', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
    Route::put('surat-keluar/{surat}', [SuratKeluarController::class, 'update'])->name('surat-keluar.update');
    Route::delete('surat-keluar/{surat}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');
    Route::get('surat-keluar/{surat}/download', [SuratKeluarController::class, 'download'])->name('surat-keluar.download');

    // Persuratan - Disposisi
    Route::get('disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');

    // Arsip
    Route::get('arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('pencarian', [PencarianController::class, 'index'])->name('pencarian.index');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Pengguna
    Route::get('pengguna', [PenggunaController::class, 'index'])
        ->middleware('role:admin')
        ->name('pengguna.index');

    // Pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';