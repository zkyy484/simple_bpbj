<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TujuanSeeder extends Seeder
{
    /**
     * Seed data Tujuan kunjungan tamu.
     */
    public function run(): void
    {
        $data = [
            ['nama_tujuan' => 'Konsultasi Pengadaan', 'status' => 'aktif'],
            ['nama_tujuan' => 'Pengambilan Dokumen', 'status' => 'aktif'],
            ['nama_tujuan' => 'Sanggah / Pengaduan', 'status' => 'aktif'],
            ['nama_tujuan' => 'Audiensi / Kerja Sama', 'status' => 'aktif'],
            ['nama_tujuan' => 'Lainnya', 'status' => 'aktif'],
        ];

        foreach ($data as $row) {
            DB::table('tujuans')->updateOrInsert(
                ['nama_tujuan' => $row['nama_tujuan']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}