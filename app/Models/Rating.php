<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tamu',
        'nilai',
        'saran'
    ];

    // Relasi: Rating milik satu Tamu
    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu');
    }
}
