<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tamu',
        'id_user',
        'role', // satpam, operator, dll
        'waktu_scan'
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    // Relasi: Log milik satu Tamu
    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu');
    }

    // Relasi: Log dilakukan oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
