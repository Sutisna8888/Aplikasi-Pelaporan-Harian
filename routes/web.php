<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLaporanController;
use App\Http\Controllers\InfoBpsController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.admin');
});

// Rute Dashboard diarahkan ke Controller
Route::get('/dashboard', [LaporanController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute Dashboard Admin
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.dashboard');

// Rute Kelola Pengguna
Route::get('/admin/pengguna', [UserController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.index');
Route::post('/admin/pengguna', [UserController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.store');
Route::put('/admin/pengguna/{id}', [UserController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.update');
Route::delete('/admin/pengguna/{id}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.destroy');

// Rute Kegiatan Admin
Route::get('/admin/kegiatan', [KegiatanController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.index');
Route::post('/admin/kegiatan', [KegiatanController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.store');
Route::put('/admin/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.update');
Route::put('/admin/kegiatan/{kegiatan}/toggle', [KegiatanController::class, 'toggleActive'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.toggle');

// Rute Kelola Laporan Admin
Route::get('/admin/laporan', [AdminLaporanController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.laporan.index');
Route::get('/admin/laporan/download', [AdminLaporanController::class, 'downloadRekap'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.laporan.download');
Route::get('/admin/laporan/{id}/download', [AdminLaporanController::class, 'downloadIndividu'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.laporan.downloadIndividu');

// Rute Info BPS
Route::get('/admin/info-bps', [InfoBpsController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.info-bps.index');
Route::post('/admin/info-bps', [InfoBpsController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.info-bps.store');
Route::delete('/admin/info-bps/{id}', [InfoBpsController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.info-bps.destroy');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::view('/profil-bps', 'profil-bps')->name('profil-bps');

    // Laporan (Pegawai)
    Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/simpan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/riwayat', [LaporanController::class, 'history'])->name('laporan.history');
    Route::put('/laporan/{id}/selesai', [LaporanController::class, 'updateSelesai'])->name('laporan.updateSelesai');
});

require __DIR__.'/auth.php';
