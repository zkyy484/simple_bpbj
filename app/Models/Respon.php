<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respon extends Model
{
    protected $table = 'respons';

    protected $primaryKey = 'id_respon';
    protected $fillable = [
        'id_tamu',
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

    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu');
    }

    // Respon yang sudah benar-benar diisi tamu (punya jawaban)
    public function scopeSudahTerisi($query)
    {
        return $query->whereHas('jawaban');
    }

    // Respon yang boleh tampil di halaman Data Survei: hanya respon milik tamu
    // yang ceklis survei-nya dicentang di Manajemen Data Tamu. Respon lama yang
    // tidak terhubung ke tamu (id_tamu null) tetap tampil.
    public function scopeTervalidasi($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('id_tamu')
                ->orWhereHas('tamu', fn ($t) => $t->where('cek_survei', true));
        });
    }

    // Apakah tamu ini sudah submit survei
    public static function tamuSudahMengisi(int $idTamu): bool
    {
        return static::where('id_tamu', $idTamu)->whereHas('jawaban')->exists();
    }

    /**
     * Buat respon kosong dari data tamu (nama, email, instansi, dan tanggal respon saat ceklis).
     * Tidak melakukan apa-apa jika respon untuk tamu ini sudah ada.
     *
     * @return bool true jika respon baru dibuat
     */
    public static function buatKosongDariTamu(Tamu $tamu): bool
    {
        if (static::where('id_tamu', $tamu->id_tamu)->exists()) {
            return false;
        }

        static::create([
            'id_tamu' => $tamu->id_tamu,
            'nama_lengkap' => mb_substr($tamu->nama_lengkap, 0, 50),
            'email' => $tamu->email ? mb_substr($tamu->email, 0, 50) : null,
            'instansi' => $tamu->nama_perusahaan ? mb_substr($tamu->nama_perusahaan, 0, 50) : null,
            'rata_rating' => null,
            'durasi_pengisian' => null,
            'tanggal_respon' => now()->toDateTimeString(),
        ]);

        return true;
    }
}