<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitStatus extends Model
{
    protected $table = 'visit_statuses';

    protected $fillable = [
        'status',
        'deskripsi',
    ];

    public function tamus(): HasMany
    {
        return $this->hasMany(Tamu::class, 'id_visit_status');
    }
}
