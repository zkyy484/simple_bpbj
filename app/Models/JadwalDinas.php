<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDinas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dinas';
    protected $primaryKey = 'id_jadwal_dinas';

    // Kolom mengikuti format tabel Jadwal:
    // NO | Bidang/Sekretariat | Acara | Surat Dari | Hari/Tanggal | Waktu | Tempat/Zoom | Yang Hadir | Keterangan
    protected $fillable = [
        'bidang_sekretariat',
        'acara',
        'surat_dari',
        'hari_tanggal',
        'waktu',
        'tempat_zoom',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'hari_tanggal' => 'date',
        ];
    }

    // Relasi ke banyak pegawai (kolom "Yang Hadir")
    public function pegawais()
    {
        return $this->belongsToMany(User::class, 'jadwal_dinas_user', 'id_jadwal_dinas', 'id_user');
    }
}