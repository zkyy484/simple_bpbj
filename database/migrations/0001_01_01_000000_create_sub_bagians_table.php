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
        Schema::create('sub_bagians', function (Blueprint $table) {
            $table->id('id_sub_bagian');

            $table->string('nama_sub_bagian', 50);

            $table->enum('status', ['aktif', 'nonaktif'])
                  ->default('aktif');

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
        Schema::dropIfExists('sub_bagians');
    }
};