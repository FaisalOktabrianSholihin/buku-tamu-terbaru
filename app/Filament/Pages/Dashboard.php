<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DivisionStatsTable;
use App\Filament\Widgets\GuestSatisfactionChart;
use App\Filament\Widgets\GuestsByDivisionChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    /**
     * Widget untuk dashboard
     */
    protected function getHeaderWidgets(): array
    {
        return [
            DivisionStatsTable::class,
            GuestSatisfactionChart::class,
            GuestsByDivisionChart::class,
        ];
    }
}
