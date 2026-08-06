<?php

use App\Http\Controllers\SuperAdmin\PertanyaanController;
use App\Http\Controllers\SuperAdmin\SurveiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SubBagianController;
use App\Http\Controllers\SuperAdmin\TujuanController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\ProfileController as PegawaiProfileController;
use App\Http\Controllers\Pegawai\TamuController as PegawaiTamuController;
use App\Http\Controllers\SuperAdmin\AkunController as SuperAkunController;
use App\Http\Controllers\SuperAdmin\TamuController as SuperAdminTamuController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperProfileController;
use App\Http\Controllers\Admin\TamuController as AdminTamuController;
use App\Http\Controllers\SuperAdmin\LaporanController as SuperAdminLaporanController;
use App\Http\Controllers\Admin\AkunController as AdminAkunController;
use App\Http\Controllers\Admin\SurveiController as AdminSurveiController;
use App\Http\Controllers\SuperAdmin\LogAktivitasController;
use Illuminate\Validation\Rule;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminDashboardController::class, 'index'])->name('super.dashboard');
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
    Route::get('/super/tujuan/arsip', [TujuanController::class, 'arsip'])->name('tujuan.arsip');
    Route::post('/super/tujuan/add', [TujuanController::class, 'store'])->name('tujuan.add');
    Route::put('/super/tujuan/update', [TujuanController::class, 'update'])->name('tujuan.update');
    Route::delete('/super/tujuan/delete', [TujuanController::class, 'softdelete'])->name('tujuan.delete');
    Route::put('/super/tujuan/pulihkan', [TujuanController::class, 'pulihkan'])->name('tujuan.pulihkan');

    // AKUN (Manajemen Akun Pegawai/Admin)
    Route::get('/super/akun', [SuperAkunController::class, 'index'])->name('index.akun');
    Route::get('/super/akun/tambah', [SuperAkunController::class, 'create'])->name('akun.create');
    Route::post('/super/akun/add', [SuperAkunController::class, 'store'])->name('akun.store');
    Route::put('/super/akun/update', [SuperAkunController::class, 'update'])->name('akun.update');
    Route::delete('/super/akun/delete', [SuperAkunController::class, 'destroy'])->name('akun.delete');

    // ARSIP AKUN
    Route::get('/super/akun/arsip', [SuperAkunController::class, 'arsip'])->name('akun.arsip');
    Route::put('/super/akun/pulihkan', [SuperAkunController::class, 'pulihkan'])->name('akun.pulihkan');


    // TAMU
    Route::get('/super/tamu', [SuperAdminTamuController::class, 'index'])->name('tamu.index');
    Route::get('/super/tamu/arsip', [SuperAdminTamuController::class, 'arsip'])->name('tamu.arsip');
    Route::patch('/super/tamu/{tamu}/approval', [SuperAdminTamuController::class, 'approval'])->name('tamu.approval');
    Route::put('/super/tamu/{tamu}', [SuperAdminTamuController::class, 'update'])->name('tamu.update');
    Route::delete('/super/tamu/{tamu}', [SuperAdminTamuController::class, 'destroy'])->name('tamu.destroy');
    Route::put('/super/tamu/{tamu}/pulihkan', [SuperAdminTamuController::class, 'pulihkan'])->name('tamu.pulihkan');

    // SURVEI PERTANYAAN
    Route::get('/pertanyaan', [PertanyaanController::class, 'index'])->name('index.pertanyaan');
    Route::post('/pertanyaan', [PertanyaanController::class, 'store'])->name('pertanyaan.store');
    Route::put('/pertanyaan/{id}', [PertanyaanController::class, 'update'])->name('pertanyaan.update');
    Route::delete('/pertanyaan/delete', [PertanyaanController::class, 'destroy'])->name('pertanyaan.destroy');
    Route::get('/super/pertanyaan/arsip', [PertanyaanController::class, 'arsip'])->name('pertanyaan.arsip');
    Route::put('/super/pertanyaan/pulihkan', [PertanyaanController::class, 'pulihkan'])->name('pertanyaan.pulihkan');

    // SURVEI TAMU
    Route::get('/super/survei', [SurveiController::class, 'index'])->name('index.survei');
    Route::get('/super/survei/arsip', [SurveiController::class, 'arsip'])->name('survei.arsip');
    Route::delete('/super/survei/delete', [SurveiController::class, 'destroy'])->name('survei.destroy');
    Route::put('/super/survei/pulihkan', [SurveiController::class, 'pulihkan'])->name('survei.pulihkan');

    // LOG AKTIFITAS
    Route::get('/super/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
});

Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN DATA TAMU
    Route::get('/tamu', [PegawaiTamuController::class, 'index'])->name('tamu.index');
    Route::put('/tamu/{id}/tindak-lanjut', [PegawaiTamuController::class, 'updateTindakLanjut'])->name('tamu.tindak-lanjut.update');
    Route::post('/tamu/{id}/kirim-email', [PegawaiTamuController::class, 'kirimEmail'])->name('tamu.kirim-email');

    // PROFILE
    Route::get('/profile', [PegawaiProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [PegawaiProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [PegawaiProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // LAPORAN
    Route::get('/super/laporan/buku-tamu', [SuperAdminLaporanController::class, 'bukuTamu'])->name('laporan.buku-tamu.index');
    Route::get('/super/laporan/buku-tamu/export', [SuperAdminLaporanController::class, 'exportBukuTamuPdf'])->name('laporan.buku-tamu.export');

    // LAPORAN PENGUNJUNG
    Route::get('/super/laporan/pengunjung', [SuperAdminLaporanController::class, 'pengunjung'])->name('laporan.pengunjung.index');
    Route::get('/super/laporan/pengunjung/export', [SuperAdminLaporanController::class, 'exportPengunjungPdf'])->name('laporan.pengunjung.export');

    // LAPORAN SURVEI TAMU
    Route::get('/super/laporan/survei', [SuperAdminLaporanController::class, 'surveiTamu'])->name('laporan.survei.index');
    Route::get('/super/laporan/survei/arsip', [SuperAdminLaporanController::class, 'arsip'])->name('laporan.survei.arsip');

    // EXPORT PDF AND EXCEL
    Route::get('laporan/survei/export', [SuperAdminLaporanController::class, 'exportSurveiTamuExcel'])
        ->name('laporan.survei.export');
    Route::get('laporan/survei/export-pdf', [SuperAdminLaporanController::class, 'exportSurveiTamuPdf'])
        ->name('laporan.survei.export.pdf');
});


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

    // AKUN (Admin)
    Route::get('/admin/akun', [AdminAkunController::class, 'index'])->name('admin.index.akun');

    // PROFILE ADMIN
    Route::get('/admin/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
    Route::put('/admin/profile/update', [AdminDashboardController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/update/password', [AdminDashboardController::class, 'UpdatePassword'])->name('admin.update.password');

    // SURVEI TAMU
    Route::get('/admin/survei', [AdminSurveiController::class, 'index'])->name('admin.survei.index');
    Route::get('/admin/survei/arsip', [AdminSurveiController::class, 'arsip'])->name('admin.survei.arsip');
    Route::delete('/admin/survei/delete', [AdminSurveiController::class, 'destroy'])->name('admin.survei.destroy');
    Route::put('/admin/survei/pulihkan', [AdminSurveiController::class, 'pulihkan'])->name('admin.survei.pulihkan');
    Route::post('admin/survei/approve', [SurveiController::class, 'approve'])->name('admin.survei.approve');
});


// BUKU TAMU (Publik, tanpa login)
Route::get('/buku-tamu', [TamuController::class, 'FormPage'])->name('tamu.form');
Route::post('/buku-tamu', [TamuController::class, 'store'])->name('tamu.store');
Route::get('/buku-tamu/terima-kasih/{tamu}', [TamuController::class, 'Thanks'])->name('thanks.page');

// SURVEI TAMU
Route::get('/survei', [TamuController::class, 'create'])->name('survei.create');
Route::post('/survei/tamu', [TamuController::class, 'storeSurvei'])->name('survei.store');
Route::get('/survei/terima-kasih', [TamuController::class, 'thankSurvei'])->name('survei.thanks');

require __DIR__ . '/auth.php';




