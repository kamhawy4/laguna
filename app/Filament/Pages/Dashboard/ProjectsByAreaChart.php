<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectsByAreaChart extends ChartWidget
{
    protected static ?string $heading = 'Projects by Area';

    protected static ?int $sort = 8;

    protected static string $color = 'success';

    protected function getData(): array
    {
        $groups = Project::query()
            ->selectRaw('area, COUNT(*) as total')
            ->whereNotNull('area')
            ->groupBy('area')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->pluck('total', 'area')
            ->toArray();

        $labels = array_keys($groups);
        $data = array_values($groups);

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => $data,
                    'backgroundColor' => [
                        '#34d399',
                        '#60a5fa',
                        '#f97316',
                        '#fb7185',
                        '#a78bfa',
                        '#f59e0b',
                        '#10b981',
                        '#ef4444',
                        '#06b6d4',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
            ],
        ];
    }
}
