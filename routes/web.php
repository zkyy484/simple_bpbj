<?php

use App\Http\Controllers\TamuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SubBagianController;
use App\Http\Controllers\Pegawai\ProfileController as PegawaiProfileController;
use App\Http\Controllers\SuperAdmin\TujuanController;
use App\Http\Controllers\Pegawai\TamuController as PegawaiTamuController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\SuperAdmin\AkunController as SuperAkunController;
use App\Http\Controllers\SuperAdmin\TamuController as SuperAdminTamuController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperProfileController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/sur-page', [TamuController::class, 'SurveiPage'])->name('sur.page');
Route::get('/sur-thanks', [TamuController::class, 'ThankSurvei'])->name('thanksur.page');


Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])
        ->name('super.dashboard');
    Route::get('/super/page', [SuperAkunController::class, 'AkunPage'])->name('super.page');

    // PROFILE AKUN
    Route::get('/super/profile', [SuperProfileController::class, 'index'])->name('super.profile');
    Route::put('/super/profile', [SuperProfileController::class, 'update'])->name('super.profile.update');

    // GANTI PASSWORD (terpisah dari update profil)
    Route::put('/super/profile/password', [SuperProfileController::class, 'updatePassword'])->name('super.profile.password.update');

    // SUB BAGIAN
    Route::get('/super/sub', [SubBagianController::class, 'Index'])->name('index.sub');
    Route::get('/super/sub/arsip', [SubBagianController::class, 'arsip'])->name('arsip.sub');
    Route::post('/super/sub/add', [SubBagianController::class, 'store'])->name('sub.store');
    Route::put('/super/sub/update', [SubBagianController::class, 'update'])->name('sub.update');
    Route::delete('/super/sub/delete', [SubBagianController::class, 'softdelete'])->name('sub.delete');
    Route::put('/super/sub/pulihkan', [SubBagianController::class, 'pulihkan'])->name('sub.pulihkan');

    // TUJUAN
    Route::get('/super/tujuan', [TujuanController::class, 'index'])->name('tujuan.index');

    // AKUN (Manajemen Akun Pegawai/Admin)
    Route::get('/super/akun', [SuperAkunController::class, 'index'])->name('index.akun');
    Route::get('/super/akun/tambah', [SuperAkunController::class, 'create'])->name('akun.create');
    Route::post('/super/akun/add', [SuperAkunController::class, 'store'])->name('akun.store');
    Route::put('/super/akun/update', [SuperAkunController::class, 'update'])->name('akun.update');
    Route::delete('/super/akun/delete', [SuperAkunController::class, 'destroy'])->name('akun.delete');

    // ARSIP AKUN
    Route::get('/super/akun/arsip', [SuperAkunController::class, 'arsip'])->name('akun.arsip');
    Route::put('/super/akun/pulihkan', [SuperAkunController::class, 'pulihkan'])->name('akun.pulihkan');
    Route::get('/super/tujuan/arsip', [TujuanController::class, 'arsip'])->name('tujuan.arsip');
    Route::post('/super/tujuan/add', [TujuanController::class, 'store'])->name('tujuan.add');
    Route::put('/super/tujuan/update', [TujuanController::class, 'update'])->name('tujuan.update');
    Route::delete('/super/tujuan/delete', [TujuanController::class, 'softdelete'])->name('tujuan.delete');
    Route::put('/super/tujuan/pulihkan', [TujuanController::class, 'pulihkan'])->name('tujuan.pulihkan');

    // TAMU
    Route::get('/super/tamu', [SuperAdminTamuController::class, 'index'])->name('tamu.index');
    Route::get('/super/tamu/arsip', [SuperAdminTamuController::class, 'arsip'])->name('tamu.arsip');
    Route::patch('/super/tamu/{tamu}/approval', [SuperAdminTamuController::class, 'approval'])->name('tamu.approval');
    Route::put('/super/tamu/{tamu}', [SuperAdminTamuController::class, 'update'])->name('tamu.update');
    Route::delete('/super/tamu/{tamu}', [SuperAdminTamuController::class, 'destroy'])->name('tamu.destroy');
    Route::put('/super/tamu/{tamu}/pulihkan', [SuperAdminTamuController::class, 'pulihkan'])->name('tamu.pulihkan');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

    Route::get('/tamu', [PegawaiTamuController::class, 'index'])->name('tamu.index');
    Route::put('/tamu/{id}/tindak-lanjut', [PegawaiTamuController::class, 'updateTindakLanjut'])->name('tamu.tindak-lanjut.update');
    Route::post('/tamu/{id}/kirim-email', [PegawaiTamuController::class, 'kirimEmail'])->name('tamu.kirim-email');

    // PROFILE
    Route::get('/profile', [PegawaiProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [PegawaiProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [PegawaiProfileController::class, 'updatePassword'])->name('profile.password.update');
});


// BUKU TAMU (Publik, tanpa login)
Route::get('/buku-tamu', [TamuController::class, 'FormPage'])->name('tamu.form');
Route::post('/buku-tamu', [TamuController::class, 'store'])->name('tamu.store');
Route::get('/buku-tamu/terima-kasih/{tamu}', [TamuController::class, 'Thanks'])->name('thanks.page');

// HALAMAN TRACKING TAMU
Route::get('/tracking/{kode_tiket}', [TamuController::class, 'show'])
    ->name('tracking.tamu');
    
require __DIR__ . '/auth.php';



