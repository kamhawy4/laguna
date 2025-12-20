<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProjects = Project::count();
        $publishedProjects = Project::where('status', 'published')->count();
        $projectsThisMonth = Project::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();

        return [
            Stat::make('Total Projects', $totalProjects)
                ->description('All projects in system')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->icon('heroicon-o-building-office-2'),

            Stat::make('Published Projects', $publishedProjects)
                ->description('Active projects')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('This Month', $projectsThisMonth)
                ->description('New projects')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->icon('heroicon-o-calendar'),
        ];
    }
}
