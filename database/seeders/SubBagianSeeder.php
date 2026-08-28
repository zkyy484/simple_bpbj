<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubBagianSeeder extends Seeder
{
    /**
     * Seed data Sub Bagian / Bidang.
     */
    public function run(): void
    {
        $data = [
            ['nama_sub_bagian' => 'Sekretariat', 'status' => 'aktif'],
            ['nama_sub_bagian' => 'Bidang Pengadaan Barang', 'status' => 'aktif'],
            ['nama_sub_bagian' => 'Bidang Pengadaan Jasa Konstruksi', 'status' => 'aktif'],
            ['nama_sub_bagian' => 'Bidang Pengadaan Jasa Konsultansi', 'status' => 'aktif'],
            ['nama_sub_bagian' => 'Bidang Pengelolaan LPSE', 'status' => 'aktif'],
            ['nama_sub_bagian' => 'Bidang Pembinaan dan Advokasi', 'status' => 'nonaktif'],
        ];

        foreach ($data as $row) {
            DB::table('sub_bagians')->updateOrInsert(
                ['nama_sub_bagian' => $row['nama_sub_bagian']],
                array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}