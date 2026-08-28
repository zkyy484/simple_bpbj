<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamuSeeder extends Seeder
{
    /**
     * Seed data Tamu (Buku Tamu Digital).
     * Berjalan setelah SubBagianSeeder, TujuanSeeder, JenisPermohonanSeeder, UserSeeder.
     */
    public function run(): void
    {
        $subBagian = DB::table('sub_bagians')->pluck('id_sub_bagian', 'nama_sub_bagian');
        $tujuan = DB::table('tujuans')->pluck('id_tujuan', 'nama_tujuan');
        $jenis = DB::table('jenis_permohonans')->pluck('id_jenis_permohonan', 'nama_jenis_permohonan');
        $pegawai = DB::table('users')->where('role', 'pegawai')->pluck('id_user')->all();

        $namaTamu = [
            'Bapak Agus Setiawan', 'Ibu Ni Putu Dewi', 'Bapak I Nyoman Sujana',
            'Ibu Ketut Purnami', 'Bapak Made Wirawan', 'Ibu Luh Ariani',
            'Bapak Kadek Suarjaya', 'Ibu Ayu Kartini', 'Bapak Wayan Suparta',
            'Ibu Dewa Ayu Manik', 'Bapak Gede Sutrisna', 'Ibu Komang Yuliati',
            'Bapak Nyoman Adi Guna', 'Ibu Putu Sinta Dewi', 'Bapak Made Bagus Krisna',
        ];

        $perusahaan = [
            'CV Bali Karya Mandiri', 'PT Dinamika Konstruksi', null,
            'CV Sinar Jaya Abadi', null, 'PT Graha Persada Consultant',
            'CV Mitra Teknik Bali', null, 'PT Nusa Bangun Sejahtera',
            'CV Adiguna Perkasa', null, 'PT Bhineka Konsultan',
            null, 'CV Wira Teknik', null, 'PT Cipta Karya Bali',
        ];

        $tujuanKeys = array_values($tujuan->keys()->all());
        $jenisKeys = array_values($jenis->keys()->all());
        $subBagianKeys = array_values($subBagian->keys()->all());

        $statusTindakLanjut = ['belum_eskalasi', 'eskalasi', 'selesai'];
        $approval = ['menunggu', 'approve'];

        foreach ($namaTamu as $i => $nama) {
            $tanggal = now()->subDays(count($namaTamu) - $i);
            $kodeTiket = 'KNS-' . $tanggal->format('Ymd') . '-' . str_pad($i + 1, 5, '0', STR_PAD_LEFT);

            DB::table('tamus')->updateOrInsert(
                ['kode_tiket' => $kodeTiket],
                [
                    'kode_tiket' => $kodeTiket,
                    'nik_nip' => '51710' . str_pad($i + 1, 10, '0', STR_PAD_LEFT),
                    'nama_lengkap' => $nama,
                    'email' => 'tamu' . ($i + 1) . '@contoh.com',
                    'nomor_telepon' => '08213456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'id_jenis_permohonan' => $jenis[$jenisKeys[$i % count($jenisKeys)]],
                    'nama_perusahaan' => $perusahaan[$i] ?? null,
                    'id_sub_bagian' => $subBagian[$subBagianKeys[$i % count($subBagianKeys)]],
                    'id_tujuan' => $tujuan[$tujuanKeys[$i % count($tujuanKeys)]],
                    'permasalahan' => 'Menanyakan proses dan kelengkapan dokumen terkait pengadaan barang/jasa.',
                    'id_user' => $pegawai[$i % max(count($pegawai), 1)] ?? null,
                    'solusi' => $i % 3 === 0 ? null : 'Sudah diarahkan dan diberikan penjelasan oleh petugas terkait.',
                    'status_tindak_lanjut' => $statusTindakLanjut[$i % count($statusTindakLanjut)],
                    'approval' => $approval[$i % count($approval)],
                    'paraf' => null,
                    'foto' => null,
                    'status_aktif' => 'aktif',
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]
            );
        }
    }
}