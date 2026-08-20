<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama Jadwal Dinas / Surat Masuk Kegiatan
        Schema::create('jadwal_dinas', function (Blueprint $table) {
            $table->id('id_jadwal_dinas');
            $table->string('nomor_agenda', 50)->nullable();
            $table->string('surat_dari', 150);
            $table->string('nomor_surat', 100);
            $table->text('perihal');
            $table->date('tanggal_surat');
            $table->date('tanggal_kegiatan'); // Patokan waktu tampil di monitor TV
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Pivot Relasi Many-to-Many ke Tabel Users (Pegawai Delegasi)
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