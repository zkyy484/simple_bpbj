<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed data akun Admin FO & Pegawai.
     * (Akun Super Admin sudah dibuat lewat SuperAdminSeeder)
     */
    public function run(): void
    {
        $subBagian = DB::table('sub_bagians')->pluck('id_sub_bagian', 'nama_sub_bagian');

        $data = [
            [
                'nama_lengkap' => 'Ni Made Ayu Wardani',
                'nip' => '198501012010012001',
                'email' => 'adminfo1@bpbj.go.id',
                'username' => 'adminfo1',
                'password' => 'password',
                'no_telepon' => '081234567891',
                'alamat' => 'Denpasar',
                'id_sub_bagian' => $subBagian['Sekretariat'] ?? null,
                'role' => 'admin_fo',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'I Kadek Surya Pratama',
                'nip' => '198702022011012002',
                'email' => 'adminfo2@bpbj.go.id',
                'username' => 'adminfo2',
                'password' => 'password',
                'no_telepon' => '081234567892',
                'alamat' => 'Denpasar',
                'id_sub_bagian' => $subBagian['Sekretariat'] ?? null,
                'role' => 'admin_fo',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'I Wayan Sudiarta',
                'nip' => '199001012015011003',
                'email' => 'wayan.sudiarta@bpbj.go.id',
                'username' => 'wayan.sudiarta',
                'password' => 'password',
                'no_telepon' => '081234567893',
                'alamat' => 'Badung',
                'id_sub_bagian' => $subBagian['Bidang Pengadaan Barang'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Ni Luh Putu Ratnasari',
                'nip' => '199102022015012004',
                'email' => 'ratnasari@bpbj.go.id',
                'username' => 'ratnasari',
                'password' => 'password',
                'no_telepon' => '081234567894',
                'alamat' => 'Gianyar',
                'id_sub_bagian' => $subBagian['Bidang Pengadaan Barang'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'I Made Dwi Antara',
                'nip' => '199203032016011005',
                'email' => 'dwi.antara@bpbj.go.id',
                'username' => 'dwi.antara',
                'password' => 'password',
                'no_telepon' => '081234567895',
                'alamat' => 'Tabanan',
                'id_sub_bagian' => $subBagian['Bidang Pengadaan Jasa Konstruksi'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Kadek Ayu Lestari',
                'nip' => '199304042016012006',
                'email' => 'ayu.lestari@bpbj.go.id',
                'username' => 'ayu.lestari',
                'password' => 'password',
                'no_telepon' => '081234567896',
                'alamat' => 'Denpasar',
                'id_sub_bagian' => $subBagian['Bidang Pengadaan Jasa Konstruksi'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'I Putu Ardika',
                'nip' => '199405052017011007',
                'email' => 'putu.ardika@bpbj.go.id',
                'username' => 'putu.ardika',
                'password' => 'password',
                'no_telepon' => '081234567897',
                'alamat' => 'Klungkung',
                'id_sub_bagian' => $subBagian['Bidang Pengadaan Jasa Konsultansi'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'Ni Kadek Sri Wahyuni',
                'nip' => '199506062017012008',
                'email' => 'sri.wahyuni@bpbj.go.id',
                'username' => 'sri.wahyuni',
                'password' => 'password',
                'no_telepon' => '081234567898',
                'alamat' => 'Denpasar',
                'id_sub_bagian' => $subBagian['Bidang Pengelolaan LPSE'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
            [
                'nama_lengkap' => 'I Gede Arya Saputra',
                'nip' => '199607072018011009',
                'email' => 'arya.saputra@bpbj.go.id',
                'username' => 'arya.saputra',
                'password' => 'password',
                'no_telepon' => '081234567899',
                'alamat' => 'Bangli',
                'id_sub_bagian' => $subBagian['Bidang Pengelolaan LPSE'] ?? null,
                'role' => 'pegawai',
                'status' => 'nonaktif',
            ],
            [
                'nama_lengkap' => 'Ni Wayan Puspita',
                'nip' => '199708082018012010',
                'email' => 'puspita@bpbj.go.id',
                'username' => 'puspita',
                'password' => 'password',
                'no_telepon' => '081234567800',
                'alamat' => 'Karangasem',
                'id_sub_bagian' => $subBagian['Bidang Pembinaan dan Advokasi'] ?? null,
                'role' => 'pegawai',
                'status' => 'aktif',
            ],
        ];

        foreach ($data as $row) {
            DB::table('users')->updateOrInsert(
                ['username' => $row['username']],
                [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'nip' => $row['nip'],
                    'email' => $row['email'],
                    'username' => $row['username'],
                    'password' => Hash::make($row['password']),
                    'no_telepon' => $row['no_telepon'],
                    'alamat' => $row['alamat'],
                    'id_sub_bagian' => $row['id_sub_bagian'],
                    'role' => $row['role'],
                    'status' => $row['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}