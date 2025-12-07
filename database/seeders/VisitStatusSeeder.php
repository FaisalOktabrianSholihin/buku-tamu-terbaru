<?php

namespace Database\Seeders;

use App\Models\VisitStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visit = [
            ['status' => 'Menunggu Validasi Satpam', 'deskripsi' => 'Tamu baru masuk dan menunggu pengecekan satpam.'],
            ['status' => 'Berhasil Validasi Satpam', 'deskripsi' => 'Satpam telah menyetujui tamu untuk masuk.'],
            ['status' => 'Berhasil Validasi Operator', 'deskripsi' => 'Operator telah memvalidasi data tamu.'],
            ['status' => 'Telah Bertemu Penerima Tamu', 'deskripsi' => 'Tamu telah bertemu dengan penerima tamu.'],
            ['status' => 'Kunjungan Selesai', 'deskripsi' => 'Tamu telah meninggalkan lokasi.'],
            ['status' => 'Kunjungan Ditolak', 'deskripsi' => 'Tamu telah ditolak.'],
        ];

        foreach ($visit as $visits) {
            VisitStatus::create($visits);
        }
    }
}
