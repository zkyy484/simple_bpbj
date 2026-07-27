<?php

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'nama_lengkap' => 'Super Administrator',
            'nip' => '000000000000000000',
            'email' => 'dzakymusyaddad@gmail.com',
            'username' => 'superadmin',
            'password' => Hash::make('11111111'),
            'no_telepon' => '081234567890',
            'alamat' => 'Denpasar',
            'jabatan' => 'Super Administrator',
            'id_sub_bagian' => null,
            'role' => 'super_admin',
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
