<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis = [
            [
                'id' => 1,
                'nama_divisi' => 'Direktur',
                'ka_divisi' => 'Vacant',
                'ket_divisi' => '-',
            ],
            [
                'id' => 2,
                'nama_divisi' => 'SEVP',
                'ka_divisi' => '-',
                'ket_divisi' => '-',
            ],
            [
                'id' => 3,
                'nama_divisi' => 'GM',
                'ka_divisi' => '-',
                'ket_divisi' => '-',
            ],
            [
                'id' => 4,
                'nama_divisi' => 'Pembenihan',
                'ka_divisi' => 'Vacant',
                'ket_divisi' => '-',
            ],
            [
                'id' => 5,
                'nama_divisi' => 'SDM',
                'ka_divisi' => 'Sholeh',
                'ket_divisi' => '-',
            ],
            [
                'id' => 6,
                'nama_divisi' => 'Keuangan dan Akuntansi',
                'ka_divisi' => 'Amboro Hidayat',
                'ket_divisi' => '-',
            ],
            [
                'id' => 7,
                'nama_divisi' => 'Pemasaran Ekspor',
                'ka_divisi' => 'Kartika Anggraeni',
                'ket_divisi' => '-',
            ],
            [
                'id' => 8,
                'nama_divisi' => 'Pemasaran Domestik',
                'ka_divisi' => 'Eko Widodo',
                'ket_divisi' => '-',
            ],
            [
                'id' => 9,
                'nama_divisi' => 'Sekper',
                'ka_divisi' => 'M. Erison Caesar Sansurya',
                'ket_divisi' => '-',
            ],
            [
                'id' => 10,
                'nama_divisi' => 'Riset dan Pengembangan (R&D)',
                'ka_divisi' => 'Yuliani',
                'ket_divisi' => '-',
            ],
            [
                'id' => 11,
                'nama_divisi' => 'Pengolahan',
                'ka_divisi' => 'Nanang Handoko',
                'ket_divisi' => '-',
            ],
            [
                'id' => 12,
                'nama_divisi' => 'Teknik dan Pemeliharaan',
                'ka_divisi' => 'Chayudi J Hidayat',
                'ket_divisi' => '-',
            ],
            [
                'id' => 13,
                'nama_divisi' => 'Budidaya (on farm)',
                'ka_divisi' => 'Wahyu Priono',
                'ket_divisi' => '-',
            ],
            [
                'id' => 14,
                'nama_divisi' => 'SPI',
                'ka_divisi' => 'Herry Setyawan',
                'ket_divisi' => '-',
            ],
            [
                'id' => 15,
                'nama_divisi' => 'Pengadaan',
                'ka_divisi' => 'Dwi Indra Pramono',
                'ket_divisi' => '-',
            ],
        ];

        foreach ($divisis as $divisi) {
            Divisi::create($divisi);
        }
    }
}
