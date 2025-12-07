<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = ['nama_divisi', 'ka_divisi', 'ket_divisi'];

    // Relasi: Divisi memiliki banyak User
    public function users()
    {
        return $this->hasMany(User::class, 'id_divisi');
    }

    // --- TAMBAHKAN INI ---
    public function tamus(): HasMany
    {
        return $this->hasMany(Tamu::class, 'id_divisi');
    }
}
