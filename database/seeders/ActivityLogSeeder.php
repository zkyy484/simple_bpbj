<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogSeeder extends Seeder
{
    /**
     * Seed data Activity Log (riwayat aktivitas akun).
     * Berjalan setelah UserSeeder.
     */
    public function run(): void
    {
        $users = DB::table('users')->get(['id_user', 'nama_lengkap', 'role']);

        $aktivitasList = [
            ['aktivitas' => 'Login', 'deskripsi' => 'Berhasil login ke sistem.'],
            ['aktivitas' => 'Logout', 'deskripsi' => 'Berhasil logout dari sistem.'],
            ['aktivitas' => 'Tambah Akun', 'deskripsi' => 'Menambahkan akun pegawai baru.'],
            ['aktivitas' => 'Ubah Data Tamu', 'deskripsi' => 'Memperbarui status tindak lanjut data tamu.'],
            ['aktivitas' => 'Approve Tamu', 'deskripsi' => 'Menyetujui (approve) data kunjungan tamu.'],
            ['aktivitas' => 'Cetak Laporan', 'deskripsi' => 'Mencetak laporan rekap survei kepuasan.'],
        ];

        $roleLog = [
            'super_admin' => 'super_admin',
            'admin_fo' => 'admin',
            'pegawai' => 'pegawai',
        ];

        $i = 0;
        foreach ($users as $user) {
            $aktivitas = $aktivitasList[$i % count($aktivitasList)];
            $waktu = now()->subHours(count($users) - $i);

            DB::table('activity_logs')->insert([
                'id_user' => $user->id_user,
                'nama_user' => $user->nama_lengkap,
                'role' => $roleLog[$user->role] ?? null,
                'aktivitas' => $aktivitas['aktivitas'],
                'deskripsi' => $aktivitas['deskripsi'],
                'ip_address' => '127.0.0.' . (($i % 254) + 1),
                'created_at' => $waktu,
            ]);

            $i++;
        }
    }
}