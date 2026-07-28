<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SubBagianController;
use App\Http\Controllers\SuperAdmin\TujuanController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\SuperAdmin\AkunController as SuperAkunController;
use App\Http\Controllers\SuperAdmin\TamuController as SuperAdminTamuController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperProfileController;
use App\Http\Controllers\Admin\TamuController as AdminTamuController;
use App\Http\Controllers\Admin\AkunController as AdminAkunController;


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

Route::middleware(['auth', 'role:pegawai'])->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiDashboardController::class, 'index'])->name('pegawai.dashboard');
});

// BUKU TAMU (Publik, tanpa login)
Route::get('/buku-tamu', [TamuController::class, 'FormPage'])->name('tamu.form');
Route::post('/buku-tamu', [TamuController::class, 'store'])->name('tamu.store');
Route::get('/buku-tamu/terima-kasih/{tamu}', [TamuController::class, 'Thanks'])->name('thanks.page');




// INI ADMIN 
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // TAMU (Admin)
    Route::get('/admin/tamu', [AdminTamuController::class, 'index'])->name('admin.tamu.index');
    Route::get('/admin/tamu/arsip', [AdminTamuController::class, 'arsip'])->name('admin.tamu.arsip');
    Route::patch('/admin/tamu/{tamu}/approval', [AdminTamuController::class, 'approval'])->name('admin.tamu.approval');
    Route::put('/admin/tamu/{tamu}', [AdminTamuController::class, 'update'])->name('admin.tamu.update');
    Route::delete('/admin/tamu/{tamu}', [AdminTamuController::class, 'destroy'])->name('admin.tamu.destroy');
    Route::put('/admin/tamu/{tamu}/pulihkan', [AdminTamuController::class, 'pulihkan'])->name('admin.tamu.pulihkan');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/akun', [AdminAkunController::class, 'index'])->name('admin.index.akun');
});

require __DIR__ . '/auth.php';

Route::get('/tes-waktu', function () {
    return now();
});


