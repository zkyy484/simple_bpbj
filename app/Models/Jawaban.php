<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $table = 'jawabans';

    protected $primaryKey = 'id_jawaban';
    protected $fillable = ['id_respon', 'id_pertanyaan', 'id_opsi', 'rating', 'jawaban'];

    public function respon()
    {
        return $this->belongsTo(Respon::class, 'id_respon', 'id_respon');
    }
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'id_pertanyaan', 'id_pertanyaan');
    }
    public function opsi()
    {
        return $this->belongsTo(Opsi::class, 'id_opsi', 'id_opsi');
    }
}