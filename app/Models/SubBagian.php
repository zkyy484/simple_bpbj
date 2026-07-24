<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBagian extends Model
{
    protected $table = 'sub_bagians';

    protected $primaryKey = 'id_sub_bagian';

    protected $fillable = [
        'nama_sub_bagian',
        'status'
    ];

    public function getRouteKeyName()
    {
        return 'id_sub_bagian';
    }
}
