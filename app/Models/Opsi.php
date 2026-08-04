<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opsi extends Model
{
    protected $table = 'opsis';

    protected $primaryKey = 'id_opsi';

    protected $fillable = ['id_pertanyaan', 'opsi', 'nilai'];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'id_pertanyaan', 'id_pertanyaan');
    }

    public function jawaban()
    {
        return $this->hasMany(
            Jawaban::class,
            'id_opsi',
            'id_opsi'
        );
    }
}