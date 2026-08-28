<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponSeeder extends Seeder
{
    /**
     * Seed data Respon survei kepuasan.
     */
    public function run(): void
    {
        $responden = [
            ['nama_lengkap' => 'Agus Setiawan', 'email' => 'agus.setiawan@contoh.com', 'instansi' => 'CV Bali Karya Mandiri'],
            ['nama_lengkap' => 'Ni Putu Dewi', 'email' => 'putu.dewi@contoh.com', 'instansi' => 'Perorangan'],
            ['nama_lengkap' => 'I Nyoman Sujana', 'email' => 'nyoman.sujana@contoh.com', 'instansi' => 'PT Dinamika Konstruksi'],
            ['nama_lengkap' => 'Ketut Purnami', 'email' => 'ketut.purnami@contoh.com', 'instansi' => 'Perorangan'],
            ['nama_lengkap' => 'Made Wirawan', 'email' => 'made.wirawan@contoh.com', 'instansi' => 'CV Sinar Jaya Abadi'],
            ['nama_lengkap' => 'Luh Ariani', 'email' => 'luh.ariani@contoh.com', 'instansi' => 'Perorangan'],
            ['nama_lengkap' => 'Kadek Suarjaya', 'email' => 'kadek.suarjaya@contoh.com', 'instansi' => 'PT Graha Persada Consultant'],
            ['nama_lengkap' => 'Ayu Kartini', 'email' => 'ayu.kartini@contoh.com', 'instansi' => 'Perorangan'],
            ['nama_lengkap' => 'Wayan Suparta', 'email' => 'wayan.suparta@contoh.com', 'instansi' => 'CV Mitra Teknik Bali'],
            ['nama_lengkap' => 'Dewa Ayu Manik', 'email' => 'dewa.manik@contoh.com', 'instansi' => 'PT Nusa Bangun Sejahtera'],
        ];

        foreach ($responden as $i => $row) {
            $tanggal = now()->subDays(count($responden) - $i)->setTime(9 + ($i % 6), 15);
            $rating = round(mt_rand(30, 50) / 10, 2); // antara 3.00 - 5.00

            DB::table('respons')->updateOrInsert(
                ['email' => $row['email']],
                [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'email' => $row['email'],
                    'instansi' => $row['instansi'],
                    'rata_rating' => $rating,
                    'status' => 'aktif',
                    'durasi_pengisian' => mt_rand(60, 300), // detik
                    'tanggal_respon' => $tanggal,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]
            );
        }
    }
}