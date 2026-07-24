<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Primary key kolom pada tabel users adalah "id_user", bukan "id" bawaan
     * Laravel (lihat migration create_users_table). Tanpa ini, Eloquent
     * (dan sistem Auth/session) akan mencari kolom "id" yang tidak ada,
     * sehingga identitas user yang tersimpan di session menjadi null dan
     * user langsung dianggap logout setelah redirect ke dashboard.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'nip',
        'email',
        'username',
        'password',
        'no_telepon',
        'alamat',
        'jabatan',
        'id_sub_bagian',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}