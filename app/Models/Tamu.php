<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Tamu extends Model
{
    protected $table = 'tamus';
    protected $primaryKey = 'id_tamu';

    protected $fillable = [
        'kode_tiket',
        'nik_nip',
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'id_jenis_permohonan',
        'nama_perusahaan',
        'id_sub_bagian',
        'id_tujuan',
        'permasalahan',
        'id_user',
        'solusi',
        'dokumen_lampiran',
        'status_tindak_lanjut',
        'approval',
        'paraf',
        'foto',
        'status_aktif',
        'cek_survei',
    ];

    protected $casts = [
        'cek_survei' => 'boolean',
    ];

    public function subBagian()
    {
        return $this->belongsTo(SubBagian::class, 'id_sub_bagian', 'id_sub_bagian');
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class, 'id_tujuan', 'id_tujuan');
    }

    public function jenisPermohonan()
    {
        return $this->belongsTo(JenisPermohonan::class, 'id_jenis_permohonan', 'id_jenis_permohonan');
    }

    public function respon()
    {
        return $this->hasOne(Respon::class, 'id_tamu', 'id_tamu');
    }

    // Link dokumen lampiran bertanda tangan (signed URL) yang kedaluwarsa otomatis
    public function urlDokumen(int $menit = 60): ?string
    {
        return $this->dokumen_lampiran
            ? URL::temporarySignedRoute('dokumen.tamu', now()->addMinutes($menit), ['id' => $this->getKey()])
            : null;
    }

    // Tamu aktif yang mengisi buku tamu pada hari ini
    public function scopeHariIni($query)
    {
        return $query->where('status_aktif', 'aktif')
            ->whereDate('created_at', now()->toDateString());
    }
}