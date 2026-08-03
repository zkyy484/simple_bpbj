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
         Schema::create('jawabans', function (Blueprint $table) {

            $table->id('id_jawaban');

            $table->foreignId('id_respon')
                ->constrained('respons', 'id_respon')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('id_pertanyaan')
                ->constrained('pertanyaans', 'id_pertanyaan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('id_opsi')
                ->nullable()
                ->constrained('opsis', 'id_opsi')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            // Diisi jika tipe pertanyaan = rating
            $table->unsignedTinyInteger('rating')->nullable();

            // Diisi jika tipe pertanyaan = textarea
            $table->text('jawaban')->nullable();

            $table->timestamps();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};
