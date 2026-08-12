<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPermohonan extends Model
{
    protected $table = 'jenis_permohonans';
    protected $primaryKey = 'id_jenis_permohonan';

    protected $fillable = [
        'nama_jenis_permohonan',
        'status',
    ];

    public function tamus()
    {
        return $this->hasMany(Tamu::class, 'id_jenis_permohonan', 'id_jenis_permohonan');
    }
}