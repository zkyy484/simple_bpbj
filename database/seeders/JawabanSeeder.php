<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JawabanSeeder extends Seeder
{
    /**
     * Seed data Jawaban untuk tiap Respon terhadap tiap Pertanyaan.
     * Berjalan setelah ResponSeeder, PertanyaanSeeder, OpsiSeeder.
     */
    public function run(): void
    {
        $respons = DB::table('respons')->pluck('id_respon');
        $pertanyaans = DB::table('pertanyaans')->get(['id_pertanyaan', 'tipe_pertanyaan']);

        $saran = [
            'Pelayanan sudah cukup baik, semoga bisa dipertahankan.',
            'Mohon ditambah loket layanan agar antrian tidak terlalu lama.',
            'Petugas sangat membantu dan informatif, terima kasih.',
            'Semoga proses administrasi bisa lebih cepat lagi ke depannya.',
            'Website dan aplikasi buku tamu digital sangat memudahkan.',
        ];

        foreach ($respons as $idRespon) {
            foreach ($pertanyaans as $pertanyaan) {
                $rating = null;
                $jawabanTeks = null;
                $idOpsi = null;

                if ($pertanyaan->tipe_pertanyaan === 'rating') {
                    $rating = mt_rand(3, 5);
                } elseif ($pertanyaan->tipe_pertanyaan === 'pilihan_ganda') {
                    $idOpsi = DB::table('opsis')
                        ->where('id_pertanyaan', $pertanyaan->id_pertanyaan)
                        ->inRandomOrder()
                        ->value('id_opsi');
                } else { // textarea
                    $jawabanTeks = $saran[array_rand($saran)];
                }

                DB::table('jawabans')->updateOrInsert(
                    [
                        'id_respon' => $idRespon,
                        'id_pertanyaan' => $pertanyaan->id_pertanyaan,
                    ],
                    [
                        'id_respon' => $idRespon,
                        'id_pertanyaan' => $pertanyaan->id_pertanyaan,
                        'id_opsi' => $idOpsi,
                        'rating' => $rating,
                        'jawaban' => $jawabanTeks,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}