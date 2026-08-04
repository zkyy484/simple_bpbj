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
        Schema::create('opsis', function (Blueprint $table) {

            $table->id('id_opsi');

            $table->foreignId('id_pertanyaan')
                ->constrained('pertanyaans', 'id_pertanyaan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('opsi', 100);
            $table->unsignedInteger('nilai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opsi');
    }
};
