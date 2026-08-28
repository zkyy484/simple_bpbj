<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PertanyaanSeeder extends Seeder
{
    /**
     * Seed data Pertanyaan survei kepuasan.
     */
    public function run(): void
    {
        $data = [
            [
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap kecepatan pelayanan yang diberikan?',
                'tipe_pertanyaan' => 'rating',
                'status' => 'aktif',
            ],
            [
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap kesopanan dan keramahan petugas?',
                'tipe_pertanyaan' => 'rating',
                'status' => 'aktif',
            ],
            [
                'pertanyaan' => 'Bagaimana penilaian Anda terhadap kejelasan informasi yang diberikan?',
                'tipe_pertanyaan' => 'rating',
                'status' => 'aktif',
            ],
            [
                'pertanyaan' => 'Dari mana Anda mengetahui layanan Buku Tamu Digital ini?',
                'tipe_pertanyaan' => 'pilihan_ganda',
                'status' => 'aktif',
            ],
            [
                'pertanyaan' => 'Apakah permasalahan/permohonan Anda terselesaikan dengan baik?',
                'tipe_pertanyaan' => 'pilihan_ganda',
                'status' => 'aktif',
            ],
            [
                'pertanyaan' => 'Kritik dan saran Anda untuk peningkatan pelayanan kami?',
                'tipe_pertanyaan' => 'textarea',
                'status' => 'aktif',
            ],
        ];

        foreach ($data as $row) {
            DB::table('pertanyaans')->updateOrInsert(
                ['pertanyaan' => $row['pertanyaan']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}