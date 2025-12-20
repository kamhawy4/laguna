<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServicesStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalServices = Service::count();
        $publishedServices = Service::where('status', 'published')->count();
        $featuredServices = Service::where('is_featured', true)->count();

        return [
            Stat::make('Total Services', $totalServices)
                ->description('All services')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info')
                ->icon('heroicon-o-wrench-screwdriver'),

            Stat::make('Published', $publishedServices)
                ->description('Active services')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Featured', $featuredServices)
                ->description('Highlighted services')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary')
                ->icon('heroicon-o-star'),
        ];
    }
}
