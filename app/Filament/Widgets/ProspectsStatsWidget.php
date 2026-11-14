<?php

namespace App\Filament\Widgets;

use App\Models\Prospect;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProspectsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $total = Prospect::count();
        $pending = Prospect::where('status', 'pendiente')->count();
        $approved = Prospect::where('status', 'aprobado')->count();
        $completed = Prospect::where('status', 'completado')->count();

        return [
            Stat::make('Total Prospects', $total)
                ->description('All captured records')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Pending', $pending)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Approved', $approved)
                ->description('Ready for onboarding')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Completed', $completed)
                ->description('Registered clients')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
        ];
    }
}
