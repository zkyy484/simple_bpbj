<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tujuan extends Model
{
    protected $table = 'tujuans';

    protected $primaryKey = 'id_tujuan';

    protected $fillable = [
        'nama_tujuan',
        'status'
    ];

    public function getRouteKeyName()
    {
        return 'id_tujuan';
    }
}
