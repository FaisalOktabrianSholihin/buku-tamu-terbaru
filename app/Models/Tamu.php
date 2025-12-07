<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamus';

    protected $fillable = [
        'nama',
        'jabatan',
        'instansi',
        'no_hp',
        'jumlah_tamu',
        'penerima_tamu',
        'nopol_kendaraan',
        'bidang_usaha',
        'id_divisi', // FK ke Divisi
        'id_status', // FK ke Status
        'id_visit_status', // FK ke visit status
        'keperluan',
        'qr_code',
        'pdf_view_count',
    ];

    /**
     * Relasi ke tabel referensi (Parent)
     */

    // Tamu mengunjungi satu Divisi
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    // Tamu memiliki satu Status saat ini
    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function visitStatus()
    {
        return $this->belongsTo(VisitStatus::class, 'id_visit_status');
    }

    public function pengiring()
    {
        return $this->hasMany(TamuPengiring::class, 'id_tamu');
    }

    // Relasi ke tabel Tanda Tangan (One-to-One)
    // Tamu hanya punya 1 baris data tanda tangan (yang berisi ttd tamu, satpam, dll)
    public function tandaTangan()
    {
        return $this->hasOne(TandaTangan::class, 'id_tamu');
    }

    /**
     * Relasi ke tabel aktivitas (Children)
     */

    // Tamu memberikan satu Rating (One-to-One)
    public function rating()
    {
        return $this->hasOne(Rating::class, 'id_tamu');
    }

    // Tamu memiliki banyak history scan (One-to-Many)
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'id_tamu');
    }
}
