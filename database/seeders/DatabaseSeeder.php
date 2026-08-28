<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan pemanggilan menjaga integritas foreign key antar tabel.
     */
    public function run(): void
    {
        $this->call([
            // Data master (tidak punya dependensi)
            SubBagianSeeder::class,
            TujuanSeeder::class,
            JenisPermohonanSeeder::class,
            PengaturanSeeder::class,

            // Akun pengguna (butuh SubBagianSeeder)
            SuperAdminSeeder::class,
            UserSeeder::class,

            // Buku Tamu Digital (butuh SubBagian, Tujuan, JenisPermohonan, User)
            TamuSeeder::class,

            // Survei Kepuasan
            PertanyaanSeeder::class,   // butuh: -
            OpsiSeeder::class,         // butuh Pertanyaan
            ResponSeeder::class,       // butuh: -
            JawabanSeeder::class,      // butuh Respon, Pertanyaan, Opsi

            // Jadwal Dinas & Monitor TV (butuh User)
            JadwalDinasSeeder::class,

            // Log Aktivitas (butuh User)
            ActivityLogSeeder::class,
        ]);
    }
}