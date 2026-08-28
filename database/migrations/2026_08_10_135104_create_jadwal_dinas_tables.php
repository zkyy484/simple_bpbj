<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama Jadwal Dinas / Surat Masuk Kegiatan
        // Kolom disesuaikan dengan format tabel Jadwal:
        // NO | Bidang/Sekretariat | Acara | Surat Dari | Hari/Tanggal | Waktu | Tempat/Zoom | Yang Hadir | Keterangan
        Schema::create('jadwal_dinas', function (Blueprint $table) {
            $table->id('id_jadwal_dinas');
            $table->string('bidang_sekretariat', 150)->nullable(); // Bidang/Sekretariat
            $table->text('acara'); // Acara
            $table->string('surat_dari', 150); // Surat Dari
            $table->date('hari_tanggal'); // Hari/Tanggal (patokan tampil di monitor TV)
            $table->time('waktu')->nullable(); // Waktu
            $table->string('tempat_zoom', 255)->nullable(); // Tempat/Zoom
            $table->text('keterangan')->nullable(); // Keterangan
            $table->timestamps();
        });

        // Tabel Pivot Relasi Many-to-Many ke Tabel Users (Yang Hadir)
        Schema::create('jadwal_dinas_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal_dinas')
                  ->constrained('jadwal_dinas', 'id_jadwal_dinas')
                  ->cascadeOnDelete();

            // Relasi ke id_user pada tabel users
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_dinas_user');
        Schema::dropIfExists('jadwal_dinas');
    }
};