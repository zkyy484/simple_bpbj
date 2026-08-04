<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('respons', function (Blueprint $table) {

            $table->id('id_respon');

            $table->string('nama_lengkap', 50);

            $table->string('email', 50)->nullable();

            $table->string('instansi', 50)->nullable();

            $table->decimal('rata_rating', 3, 2)->nullable();

            $table->enum('status', [
                'aktif',
                'nonaktif'
            ])->default('aktif');

            $table->enum('cek', [
                'menunggu',
                'approve'
            ])->default('menunggu');

            $table->integer('durasi_pengisian')->nullable();

            $table->dateTime('tanggal_respon');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon');
    }
};
