<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    /**
     * Seed data Pengaturan umum aplikasi (contoh: link video TV Display).
     * Catatan: key 'display_link_video' sudah dibuat otomatis oleh migration
     * create_pengaturans_table, seeder ini hanya mengisi contoh nilainya.
     */
    public function run(): void
    {
        DB::table('pengaturans')->updateOrInsert(
            ['key' => 'display_link_video'],
            [
                'key' => 'display_link_video',
                'value' => json_encode([
                    'https://youtu.be/dQw4w9WgXcQ',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}