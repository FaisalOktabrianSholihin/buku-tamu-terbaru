<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TamuPengiring extends Model
{
    protected $table = 'tamu_pengirings';

    protected $fillable = [
        'id_tamu',
        'nama',
        'jabatan',
    ];
}
