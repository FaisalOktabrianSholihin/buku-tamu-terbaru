<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TandaTangan extends Model
{
    protected $table = 'tanda_tangans';

    protected $fillable = [
        'id_tamu',
        'ttd_tamu',
        'ttd_satpam',
        'nama_satpam',   // <--- Tambahkan ini
        'ttd_operator',
        'nama_operator', // <--- Tambahkan ini
        'ttd_penerima',
    ];

    // Relasi balik ke tabel Tamu (opsional, tapi berguna nanti)
    public function tamu()
    {
        return $this->belongsTo(Tamu::class, 'id_tamu');
    }
}
