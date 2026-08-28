<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalDinasSeeder extends Seeder
{
    /**
     * Seed data Jadwal Dinas beserta relasi pegawai yang hadir (pivot).
     * Berjalan setelah UserSeeder.
     */
    public function run(): void
    {
        $pegawaiIds = DB::table('users')->pluck('id_user')->all();

        $data = [
            [
                'bidang_sekretariat' => 'Sekretariat',
                'acara' => 'Rapat Koordinasi Pengadaan Barang/Jasa Triwulan III',
                'surat_dari' => 'Sekretaris Daerah',
                'hari_tanggal' => now()->addDays(1)->toDateString(),
                'waktu' => '09:00:00',
                'tempat_zoom' => 'Ruang Rapat Utama Lt. 2',
                'keterangan' => 'Wajib dihadiri seluruh kepala bidang.',
            ],
            [
                'bidang_sekretariat' => 'Bidang Pengadaan Barang',
                'acara' => 'Sosialisasi Aplikasi LPSE Versi Terbaru',
                'surat_dari' => 'LKPP RI',
                'hari_tanggal' => now()->addDays(2)->toDateString(),
                'waktu' => '13:00:00',
                'tempat_zoom' => 'https://zoom.us/j/123456789',
                'keterangan' => 'Melalui Zoom Meeting, tautan menyusul.',
            ],
            [
                'bidang_sekretariat' => 'Bidang Pengadaan Jasa Konstruksi',
                'acara' => 'Bimbingan Teknis Kontrak Konstruksi',
                'surat_dari' => 'Dinas Pekerjaan Umum',
                'hari_tanggal' => now()->addDays(3)->toDateString(),
                'waktu' => '08:30:00',
                'tempat_zoom' => 'Aula Dinas PU',
                'keterangan' => null,
            ],
            [
                'bidang_sekretariat' => 'Bidang Pengadaan Jasa Konsultansi',
                'acara' => 'Audiensi Asosiasi Konsultan Bali',
                'surat_dari' => 'INKINDO Bali',
                'hari_tanggal' => now()->toDateString(),
                'waktu' => '10:00:00',
                'tempat_zoom' => 'Ruang Rapat Bidang Konsultansi',
                'keterangan' => 'Membahas evaluasi kinerja penyedia jasa konsultansi.',
            ],
            [
                'bidang_sekretariat' => 'Bidang Pengelolaan LPSE',
                'acara' => 'Pemeliharaan Server dan Sistem LPSE',
                'surat_dari' => 'Tim IT Internal',
                'hari_tanggal' => now()->addDays(4)->toDateString(),
                'waktu' => '19:00:00',
                'tempat_zoom' => 'Ruang Server',
                'keterangan' => 'Dilaksanakan di luar jam layanan.',
            ],
            [
                'bidang_sekretariat' => 'Bidang Pembinaan dan Advokasi',
                'acara' => 'Pendampingan Hukum Sengketa Pengadaan',
                'surat_dari' => 'Biro Hukum Pemprov',
                'hari_tanggal' => now()->addDays(5)->toDateString(),
                'waktu' => '14:00:00',
                'tempat_zoom' => 'Ruang Rapat Biro Hukum',
                'keterangan' => null,
            ],
        ];

        foreach ($data as $row) {
            $idJadwal = DB::table('jadwal_dinas')->insertGetId(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Tempel 2-3 pegawai acak sebagai "Yang Hadir" pada pivot table
            $hadir = collect($pegawaiIds)->shuffle()->take(min(3, count($pegawaiIds)));

            foreach ($hadir as $idUser) {
                DB::table('jadwal_dinas_user')->updateOrInsert([
                    'id_jadwal_dinas' => $idJadwal,
                    'id_user' => $idUser,
                ]);
            }
        }
    }
}