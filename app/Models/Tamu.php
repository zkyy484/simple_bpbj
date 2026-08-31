<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'status_tindak_lanjut',
        'approval',
        'paraf',
        'foto',
        'status_aktif',
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

    
}