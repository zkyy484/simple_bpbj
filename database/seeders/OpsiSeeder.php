<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpsiSeeder extends Seeder
{
    /**
     * Seed data Opsi jawaban untuk pertanyaan bertipe pilihan_ganda.
     * Berjalan setelah PertanyaanSeeder.
     */
    public function run(): void
    {
        $idSumber = DB::table('pertanyaans')
            ->where('pertanyaan', 'Dari mana Anda mengetahui layanan Buku Tamu Digital ini?')
            ->value('id_pertanyaan');

        $idSelesai = DB::table('pertanyaans')
            ->where('pertanyaan', 'Apakah permasalahan/permohonan Anda terselesaikan dengan baik?')
            ->value('id_pertanyaan');

        $data = [];

        if ($idSumber) {
            $data = array_merge($data, [
                ['id_pertanyaan' => $idSumber, 'opsi' => 'Media Sosial', 'nilai' => 1],
                ['id_pertanyaan' => $idSumber, 'opsi' => 'Website Resmi', 'nilai' => 2],
                ['id_pertanyaan' => $idSumber, 'opsi' => 'Rekomendasi Teman/Kolega', 'nilai' => 3],
                ['id_pertanyaan' => $idSumber, 'opsi' => 'Datang Langsung', 'nilai' => 4],
            ]);
        }

        if ($idSelesai) {
            $data = array_merge($data, [
                ['id_pertanyaan' => $idSelesai, 'opsi' => 'Ya, Terselesaikan', 'nilai' => 1],
                ['id_pertanyaan' => $idSelesai, 'opsi' => 'Sebagian Terselesaikan', 'nilai' => 2],
                ['id_pertanyaan' => $idSelesai, 'opsi' => 'Belum Terselesaikan', 'nilai' => 3],
            ]);
        }

        foreach ($data as $row) {
            DB::table('opsis')->updateOrInsert(
                ['id_pertanyaan' => $row['id_pertanyaan'], 'opsi' => $row['opsi']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}