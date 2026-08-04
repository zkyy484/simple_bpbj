<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respon extends Model
{
    protected $table = 'respons';

    protected $primaryKey = 'id_respon';
    protected $fillable = [
        'nama_lengkap',
        'email',
        'instansi',
        'rata_rating',
        'status',
        'cek',
        'durasi_pengisian',
        'tanggal_respon'
    ];

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'id_respon', 'id_respon');
    }
}