<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pengaturan umum aplikasi (key-value sederhana).
     * Dipakai antara lain untuk menyimpan Link Video Display (YouTube)
     * yang ditampilkan di layar TV Display saat tidak ada jadwal dinas.
     *
     * Kolom 'value' bertipe text sengaja dipakai (bukan string) agar cukup
     * menampung JSON array berisi lebih dari 1 link video sekaligus urutan
     * tayangnya, contoh: ["https://youtu.be/aaa", "https://youtu.be/bbb"].
     * Lihat App\Models\Pengaturan::displayVideoLinks() / displayVideoEmbeds()
     * untuk logika baca-tulisnya.
     */
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable(); // menampung 1 nilai teks biasa ATAU JSON array (mis. daftar link video)
            $table->timestamps();
        });

        // Seed default key agar langsung tersedia.
        // Value awal null = belum ada video diatur. Setelah admin menyimpan
        // lewat halaman Pengaturan Display Online, value akan berisi JSON
        // array link video, contoh: ["https://youtu.be/aaa","https://youtu.be/bbb"]
        \Illuminate\Support\Facades\DB::table('pengaturans')->insert([
            'key' => 'display_link_video',
            'value' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};