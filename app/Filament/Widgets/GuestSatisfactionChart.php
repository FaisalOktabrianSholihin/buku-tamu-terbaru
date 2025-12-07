<?php

// namespace App\Filament\Widgets;

// use Filament\Widgets\ChartWidget;

// class GuestSatisfactionChart extends ChartWidget
// {
//     protected ?string $heading = 'Guest Satisfaction Chart';

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

use App\Models\Rating;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GuestSatisfactionChart extends ChartWidget
{
    protected ?string $heading = 'Tingkat Kepuasan Tamu';

    protected static ?int $sort = 2;

    // public static function canView(): bool
    // {
    //     return auth()->user()?->can('View:GuestStatisfactionChart') ?? false;
    // }

    protected function getData(): array
    {
        // Mengelompokkan berdasarkan nilai rating (misal: 1, 2, 3, 4, 5)
        $data = Rating::select('nilai', DB::raw('count(*) as total'))
            ->groupBy('nilai')
            ->orderBy('nilai', 'asc') // Urutkan dari nilai terkecil ke terbesar
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Feedback',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#22c55e', // Warna hijau
                ],
            ],
            // Label sumbu X adalah nilai ratingnya
            'labels' => $data->pluck('nilai')->map(fn($val) => "Bintang $val")->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bar chart biasanya lebih mudah dibaca untuk rating
    }
}
