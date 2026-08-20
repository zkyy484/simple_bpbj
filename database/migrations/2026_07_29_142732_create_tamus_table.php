<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tamus', function (Blueprint $table) {

            $table->id('id_tamu');

            // Kode tiket unik untuk tracking kunjungan, contoh: KNS-2026072700001
            $table->string('kode_tiket', 30)->unique();

            $table->string('nik_nip', 30)->nullable();

            $table->string('nama_lengkap', 100);

            $table->string('email', 100)->nullable();

            $table->string('nomor_telepon', 20)->nullable();

            $table->foreignId('id_jenis_permohonan')
                  ->nullable()
                  ->constrained('jenis_permohonans', 'id_jenis_permohonan')
                  ->nullOnDelete();

            $table->string('nama_perusahaan', 100)->nullable();

            $table->foreignId('id_sub_bagian')
                  ->nullable()
                  ->constrained('sub_bagians', 'id_sub_bagian')
                  ->nullOnDelete();

            $table->foreignId('id_tujuan')
                  ->nullable()
                  ->constrained('tujuans', 'id_tujuan')
                  ->nullOnDelete();

            $table->text('permasalahan')->nullable();

            // Pegawai yang menangani tamu ini (diisi belakangan oleh admin)
            $table->foreignId('id_user')
                  ->nullable()
                  ->constrained('users', 'id_user')
                  ->nullOnDelete();

            $table->text('solusi')->nullable();

            $table->enum('status_tindak_lanjut', [
                'belum_eskalasi',
                'eskalasi',
                'selesai',
            ])->default('belum_eskalasi');

            $table->enum('approval', [
                'menunggu',
                'approve',
            ])->default('menunggu');

            // Paraf pegawai/admin yang melakukan approval terhadap tamu ini
            $table->longText('paraf')->nullable();

            // Foto tamu yang diambil langsung dari kamera saat mengisi
            // Buku Tamu Digital. Disimpan sebagai base64 data URL,
            // konsisten dengan pola penyimpanan kolom `paraf`.
            $table->longText('foto')->nullable();

            // Status aktif/non-aktif data tamu (pengganti soft delete)
            $table->enum('status_aktif', [
                'aktif',
                'non_aktif',
            ])->default('aktif');

            $table->timestamp('created_at')->useCurrent();

            $table->timestamp('updated_at')
                  ->useCurrent()
                  ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};