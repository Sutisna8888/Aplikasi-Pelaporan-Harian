<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
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
Route::get('/admin/pengguna', [\App\Http\Controllers\UserController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.index');
Route::post('/admin/pengguna', [\App\Http\Controllers\UserController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.store');
Route::put('/admin/pengguna/{id}', [\App\Http\Controllers\UserController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.update');
Route::delete('/admin/pengguna/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.pengguna.destroy');

// Rute Kegiatan Admin
Route::get('/admin/kegiatan', [\App\Http\Controllers\KegiatanController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.index');
Route::post('/admin/kegiatan', [\App\Http\Controllers\KegiatanController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.store');
Route::put('/admin/kegiatan/{kegiatan}', [\App\Http\Controllers\KegiatanController::class, 'update'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.update');
Route::put('/admin/kegiatan/{kegiatan}/toggle', [\App\Http\Controllers\KegiatanController::class, 'toggleActive'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.kegiatan.toggle');

// Rute Kelola Laporan Admin
Route::get('/admin/laporan', [\App\Http\Controllers\AdminLaporanController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.laporan.index');
Route::get('/admin/laporan/download', [\App\Http\Controllers\AdminLaporanController::class, 'downloadRekap'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.laporan.download');
Route::get('/admin/laporan/{id}/download', [\App\Http\Controllers\AdminLaporanController::class, 'downloadIndividu'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.laporan.downloadIndividu');

// Rute Info BPS
Route::get('/admin/info-bps', [\App\Http\Controllers\InfoBpsController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.info-bps.index');
Route::post('/admin/info-bps', [\App\Http\Controllers\InfoBpsController::class, 'store'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.info-bps.store');
Route::delete('/admin/info-bps/{id}', [\App\Http\Controllers\InfoBpsController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.info-bps.destroy');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/laporan/riwayat', [LaporanController::class, 'history'])->name('laporan.history');
    Route::put('/laporan/{id}/selesai', [LaporanController::class, 'updateSelesai'])->name('laporan.updateSelesai');
    Route::view('/profil-bps', 'profil-bps')->name('profil-bps');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/laporan/buat', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan/simpan', [LaporanController::class, 'store'])->name('laporan.store');
});
 
require __DIR__.'/auth.php';