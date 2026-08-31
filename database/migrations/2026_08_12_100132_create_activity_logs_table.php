<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id('id_log');

            // Nullable & nullOnDelete supaya riwayat log tetap ada
            // walaupun akun user yang bersangkutan dihapus/diarsipkan.
            $table->foreignId('id_user')
                  ->nullable()
                  ->constrained('users', 'id_user')
                  ->nullOnDelete();

            // Snapshot nama & role saat kejadian terjadi, supaya log tetap
            // informatif meskipun data user berubah di kemudian hari.
            $table->string('nama_user', 50)->nullable();

            $table->enum('role', [
                'super_admin',
                'admin_fo',
                'pegawai',
            ])->nullable();

            // Judul singkat aktivitas, contoh: "Login", "Logout", "Tambah Akun"
            $table->string('aktivitas', 100);

            // Penjelasan detail aktivitas
            $table->text('deskripsi')->nullable();

            $table->string('ip_address', 45)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['role']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};