<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;

// class GuestsByDivisionChart extends ChartWidget
// {
//     protected ?string $heading = 'Guests By Division Chart';

//     protected function getData(): array
//     {
//         return [
//             //
//         ];
//     }

//     protected function getType(): string
//     {
//         return 'pie';
//     }
// }

namespace App\Filament\Widgets;

use App\Models\Tamu;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GuestsByDivisionChart extends ChartWidget
{
    protected ?string $heading = 'Statistik Tujuan Tamu per Divisi';

    // Opsi: Urutkan posisi widget
    protected static ?int $sort = 1;

    // public static function canView(): bool
    // {
    //     return auth()->user()?->can('View:GuestByDivisionChart') ?? false;
    // }

    protected function getData(): array
    {
        // Mengambil data jumlah tamu dikelompokkan berdasarkan divisi
        $data = Tamu::select('id_divisi', DB::raw('count(*) as total'))
            ->with('divisi') // Eager load relasi divisi
            ->groupBy('id_divisi')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tamu',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#ef4444',
                        '#22c55e',
                        '#eab308',
                        '#a855f7',
                        '#ec4899',
                        '#6366f1',
                        '#14b8a6',
                        '#f97316',
                        '#64748b',
                    ],
                ],
            ],
            // Mengambil nama divisi dari relasi
            'labels' => $data->pluck('divisi.nama_divisi')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
