<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDinas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_dinas';
    protected $primaryKey = 'id_jadwal_dinas';

    protected $fillable = [
        'nomor_agenda',
        'surat_dari',
        'nomor_surat',
        'perihal',
        'tanggal_surat',
        'tanggal_kegiatan',
        'keterangan',
    ];

    // Relasi ke banyak pegawai
    public function pegawais()
    {
        return $this->belongsToMany(User::class, 'jadwal_dinas_user', 'id_jadwal_dinas', 'id_user');
    }
}