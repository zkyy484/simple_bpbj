<?php

use App\Http\Controllers\SuperAdmin\JadwalDinasController;
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
use App\Http\Controllers\SuperAdmin\PengaturanController;
use Illuminate\Validation\Rule;
use App\Http\Controllers\SuperAdmin\JenisPermohonanController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/super/dashboard', [SuperAdminDashboardController::class, 'index'])->name('super.dashboard');
    // PROFILE AKUN
    Route::get('/super/profile', [SuperProfileController::class, 'index'])->name('super.profile');
    Route::put('/super/profile', [SuperProfileController::class, 'update'])->name('super.profile.update');

    // GANTI PASSWORD (terpisah dari update profil)
    Route::put('/super/profile/password', [SuperProfileController::class, 'updatePassword'])->name('super.profile.password.update');

    // SUB BAGIAN
    Route::get('/super/sub', [SubBagianController::class, 'Index'])->name('super.sub.index');
    Route::get('/super/sub/arsip', [SubBagianController::class, 'arsip'])->name('super.sub.arsip');
    Route::post('/super/sub/add', [SubBagianController::class, 'store'])->name('super.sub.store');
    Route::put('/super/sub/update', [SubBagianController::class, 'update'])->name('super.sub.update');
    Route::delete('/super/sub/delete', [SubBagianController::class, 'softdelete'])->name('super.sub.delete');
    Route::put('/super/sub/pulihkan', [SubBagianController::class, 'pulihkan'])->name('super.sub.pulihkan');

    // TUJUAN
    Route::get('/super/tujuan', [TujuanController::class, 'index'])->name('super.tujuan.index');
    Route::get('/super/tujuan/arsip', [TujuanController::class, 'arsip'])->name('super.tujuan.arsip');
    Route::post('/super/tujuan/add', [TujuanController::class, 'store'])->name('super.tujuan.add');
    Route::put('/super/tujuan/update', [TujuanController::class, 'update'])->name('super.tujuan.update');
    Route::delete('/super/tujuan/delete', [TujuanController::class, 'softdelete'])->name('super.tujuan.delete');
    Route::put('/super/tujuan/pulihkan', [TujuanController::class, 'pulihkan'])->name('super.tujuan.pulihkan');

    // AKUN (Manajemen Akun Pegawai/Admin)
    Route::get('/super/akun', [SuperAkunController::class, 'index'])->name('super.akun.index');
    Route::get('/super/akun/tambah', [SuperAkunController::class, 'create'])->name('super.akun.create');
    Route::post('/super/akun/add', [SuperAkunController::class, 'store'])->name('super.akun.store');
    Route::put('/super/akun/update', [SuperAkunController::class, 'update'])->name('super.akun.update');
    Route::delete('/super/akun/delete', [SuperAkunController::class, 'destroy'])->name('super.akun.delete');

    // ARSIP AKUN
    Route::get('/super/akun/arsip', [SuperAkunController::class, 'arsip'])->name('super.akun.arsip');
    Route::put('/super/akun/pulihkan', [SuperAkunController::class, 'pulihkan'])->name('super.akun.pulihkan');

    // TAMU
    Route::get('/super/tamu', [SuperAdminTamuController::class, 'index'])->name('super.tamu.index');
    Route::get('/super/tamu/arsip', [SuperAdminTamuController::class, 'arsip'])->name('super.tamu.arsip');
    Route::patch('/super/tamu/{tamu}/approval', [SuperAdminTamuController::class, 'approval'])->name('super.tamu.approval');
    Route::put('/super/tamu/{tamu}', [SuperAdminTamuController::class, 'update'])->name('super.tamu.update');
    Route::delete('/super/tamu/{tamu}', [SuperAdminTamuController::class, 'destroy'])->name('super.tamu.destroy');
    Route::put('/super/tamu/{tamu}/pulihkan', [SuperAdminTamuController::class, 'pulihkan'])->name('super.tamu.pulihkan');

    // SURVEI PERTANYAAN
    Route::get('/super/pertanyaan', [PertanyaanController::class, 'index'])->name('super.pertanyaan.index');
    Route::post('/super/pertanyaan', [PertanyaanController::class, 'store'])->name('super.pertanyaan.store');
    Route::put('/super/pertanyaan/{id}', [PertanyaanController::class, 'update'])->name('super.pertanyaan.update');
    Route::delete('/super/pertanyaan/delete', [PertanyaanController::class, 'destroy'])->name('super.pertanyaan.destroy');
    Route::get('/super/pertanyaan/arsip', [PertanyaanController::class, 'arsip'])->name('super.pertanyaan.arsip');
    Route::put('/super/pertanyaan/pulihkan', [PertanyaanController::class, 'pulihkan'])->name('super.pertanyaan.pulihkan');

    // SURVEI TAMU
    Route::get('/super/survei', [SurveiController::class, 'index'])->name('super.survei.index');
    Route::get('/super/survei/arsip', [SurveiController::class, 'arsip'])->name('super.survei.arsip');
    Route::delete('/super/survei/delete', [SurveiController::class, 'destroy'])->name('super.survei.destroy');
    Route::put('/super/survei/pulihkan', [SurveiController::class, 'pulihkan'])->name('super.survei.pulihkan');

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

    // JADWAL DINAS
Route::get('/super/jadwal-dinas', [JadwalDinasController::class, 'index'])->name('super.jadwal_dinas.index');
Route::post('/super/jadwal-dinas/add', [JadwalDinasController::class, 'store'])->name('super.jadwal_dinas.store');
Route::put('/super/jadwal-dinas/update/{id}', [JadwalDinasController::class, 'update'])->name('super.jadwal_dinas.update');
Route::delete('/super/jadwal-dinas/delete/{id}', [JadwalDinasController::class, 'destroy'])->name('super.jadwal_dinas.destroy');

    // JENIS PERMOHONAN
    Route::get('/super/jenis-permohonan', [JenisPermohonanController::class, 'index'])->name('super.jenis.index');
    Route::get('/super/jenis-permohonan/arsip', [JenisPermohonanController::class, 'arsip'])->name('super.jenis.arsip');
    Route::post('/super/jenis-permohonan/add', [JenisPermohonanController::class, 'store'])->name('super.jenis.store');
    Route::put('/super/jenis-permohonan/update', [JenisPermohonanController::class, 'update'])->name('super.jenis.update');
    Route::delete('/super/jenis-permohonan/delete', [JenisPermohonanController::class, 'softdelete'])->name('super.jenis.delete');
    Route::put('/super/jenis-permohonan/pulihkan', [JenisPermohonanController::class, 'pulihkan'])->name('super.jenis.pulihkan');

    // LOG AKTIFITAS
    Route::get('/super/log-aktivitas', [LogAktivitasController::class, 'index'])->name('super.log-aktivitas.index');
});

Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

    // MANAJEMEN DATA TAMU
    Route::get('/tamu', [PegawaiTamuController::class, 'index'])->name('tamu.index');
    Route::put('/tamu/{id}/tindak-lanjut', [PegawaiTamuController::class, 'updateTindakLanjut'])->name('tamu.tindak-lanjut.update');
    Route::put('/tamu/{id}/kirim-email', [PegawaiTamuController::class, 'kirimEmail'])->name('tamu.kirim-email');
    Route::put('/tamu/{id}/terima', [PegawaiTamuController::class, 'terimaTamu'])->name('tamu.terima');

    // PROFILE
    Route::get('/profile', [PegawaiProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [PegawaiProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [PegawaiProfileController::class, 'updatePassword'])->name('profile.password.update');
});


// INI ADMIN
Route::middleware(['auth', 'role:admin_fo'])->group(function () {
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
});


// BUKU TAMU (Publik, tanpa login)
Route::get('/buku-tamu', [TamuController::class, 'FormPage'])->name('tamu.form');
Route::post('/buku-tamu', [TamuController::class, 'store'])->name('tamu.store');
Route::get('/buku-tamu/terima-kasih/{tamu}', [TamuController::class, 'Thanks'])->name('thanks.page');

// SURVEI TAMU
Route::get('/survei', [TamuController::class, 'create'])->name('survei.create');
Route::post('/survei/tamu', [TamuController::class, 'storeSurvei'])->name('survei.store');
Route::get('/survei/terima-kasih', [TamuController::class, 'thankSurvei'])->name('survei.thanks');

// DSIPLAY
Route::get('/dis', [JadwalDinasController::class, 'displayTV'])->name('display.tv');
Route::get('/dis/stats', [JadwalDinasController::class, 'displayStats'])->name('display.stats');
Route::get('/dis/jadwal', [JadwalDinasController::class, 'displayJadwal'])->name('display.jadwal');

// PENGATURAN DISPLAY ONLINE (LINK VIDEO YOUTUBE)
Route::get('/super/pengaturan-display', [PengaturanController::class, 'index'])->name('super.pengaturan.index');
Route::put('/super/pengaturan-display', [PengaturanController::class, 'update'])->name('super.pengaturan.update');


require __DIR__ . '/auth.php';