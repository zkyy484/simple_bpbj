<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPermohonanSeeder extends Seeder
{
    /**
     * Seed data Jenis Permohonan tamu.
     */
    public function run(): void
    {
        $data = [
            ['nama_jenis_permohonan' => 'Informasi Umum', 'status' => 'aktif'],
            ['nama_jenis_permohonan' => 'Pengaduan', 'status' => 'aktif'],
            ['nama_jenis_permohonan' => 'Konsultasi Teknis', 'status' => 'aktif'],
            ['nama_jenis_permohonan' => 'Permintaan Data / Dokumen', 'status' => 'aktif'],
            ['nama_jenis_permohonan' => 'Kunjungan Dinas', 'status' => 'aktif'],
        ];

        foreach ($data as $row) {
            DB::table('jenis_permohonans')->updateOrInsert(
                ['nama_jenis_permohonan' => $row['nama_jenis_permohonan']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}