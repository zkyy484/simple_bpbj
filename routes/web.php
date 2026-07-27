<?php

use App\Http\Controllers\SuperAdmin\SubBagianController;
use App\Http\Controllers\SuperAdmin\TujuanController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\SuperAdmin\AkunController as SuperAkunController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])
        ->name('pegawai.dashboard');
});

require __DIR__ . '/auth.php';

Route::get('/tujuan', function () {
    return view('super-admin.tujuan.index');
});

Route::get('/tamu', function () {
    return view('super-admin.tamu.index');
})->name('super-admin.tamu.index');

// Route untuk halaman detail (modal)
Route::get('/detailtamu', function () {
    return view('super-admin.tamu.show');
})->name('super-admin.tamu.show');

Route::get('/arsiptamu', function () {
    return view('super-admin.tamu.arsip');
})->name('super-admin.tamu.arsip');