<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_permohonans', function (Blueprint $table) {
            $table->id('id_jenis_permohonan');
            $table->string('nama_jenis_permohonan', 50);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamp('created_at')->useCurrent();

            $table->timestamp('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_permohonans');
    }
};