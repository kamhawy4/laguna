<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Contact;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ContactsLineChart extends ChartWidget
{
    protected static ?string $heading = 'Leads Over Time (Last 30 Days)';

    protected static ?int $sort = 7;

    protected static string $color = 'info';

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last 30 days',
        ];
    }

    protected function getData(): array
    {
        $days = match($this->filter) {
            'today' => 1,
            'week' => 7,
            'month' => 30,
            default => 30,
        };

        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $data = [];
        $labels = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $labels[] = $date->format('M d');
            
            $count = Contact::whereBetween('created_at', [
                $date->startOfDay(),
                $date->endOfDay()
            ])->count();
            
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Leads',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
