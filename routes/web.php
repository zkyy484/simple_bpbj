<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\SubBagianController;
use App\Http\Controllers\SuperAdmin\TujuanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\SuperAdmin\AkunController as SuperAkunController;


Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])
        ->name('super.dashboard');
    Route::get('/super/page', [SuperAkunController::class, 'AkunPage'])->name('index.akun');


    // SUB BAGIAN
    Route::get('/super/sub', [SubBagianController::class, 'Index'])->name('index.sub');
    Route::get('/super/sub/arsip', [SubBagianController::class, 'arsip'])->name('arsip.sub');
    Route::post('/super/sub/add', [SubBagianController::class, 'store'])->name('sub.store');
    Route::put('/super/sub/update', [SubBagianController::class, 'update'])->name('sub.update');
    Route::delete('/super/sub/delete', [SubBagianController::class, 'softdelete'])->name('sub.delete');
    Route::put('/super/sub/pulihkan', [SubBagianController::class, 'pulihkan'])->name('sub.pulihkan');


    // TUJUAN
    Route::get('/super/tujuan', [TujuanController::class, 'index'])->name('tujuan.index');
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