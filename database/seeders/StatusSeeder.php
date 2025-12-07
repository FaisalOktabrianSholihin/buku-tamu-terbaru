<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => 1,
                'nama_status' => 'Suplier',
                'ket_status' => '-',
            ],
            [
                'id' => 2,
                'nama_status' => 'Customer/Buyer',
                'ket_status' => '-',
            ],
            [
                'id' => 3,
                'nama_status' => 'Umum',
                'ket_status' => '-',
            ],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
