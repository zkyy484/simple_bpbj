<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Pertanyaan.php
class Pertanyaan extends Model
{
    protected $primaryKey = 'id_pertanyaan';
    protected $fillable = ['pertanyaan', 'tipe_pertanyaan', 'urutan', 'status'];

    public function opsi()
    {
        return $this->hasMany(Opsi::class, 'id_pertanyaan', 'id_pertanyaan')->orderBy('id_opsi');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'id_pertanyaan', 'id_pertanyaan');
    }

    // Cek apakah pertanyaan ini sudah pernah dijawab responden
    public function sudahAdaRespon(): bool
    {
        return $this->jawaban()->exists();
    }
}

