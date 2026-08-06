<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $primaryKey = 'id_log';

    /**
     * Tabel ini hanya punya created_at (tidak ada updated_at),
     * karena sebuah log tidak pernah diubah setelah dibuat.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'id_user',
        'nama_user',
        'role',
        'aktivitas',
        'deskripsi',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Label role yang enak dibaca untuk ditampilkan di UI.
     */
    public static function roleLabel(?string $role): string
    {
        return match ($role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'pegawai' => 'Pegawai',
            default => '-',
        };
    }

    /**
     * Helper utama untuk mencatat satu baris log aktivitas.
     *
     * Contoh pemakaian:
     *   ActivityLog::catat('Login', 'Berhasil login ke sistem.');
     *   ActivityLog::catat('Tambah Akun', "Menambahkan akun pegawai atas nama {$nama}.");
     *
     * @param  string       $aktivitas  Judul singkat aktivitas (mis. "Login", "Tambah Akun")
     * @param  string|null  $deskripsi  Penjelasan detail aktivitas
     * @param  User|null    $user       User pelaku aktivitas. Default: user yang sedang login.
     */
    public static function catat(string $aktivitas, ?string $deskripsi = null, ?User $user = null): self
    {
        $user = $user ?? Auth::user();

        return self::create([
            'id_user' => $user->id_user ?? null,
            'nama_user' => $user->nama_lengkap ?? 'Sistem',
            'role' => $user->role ?? null,
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}