<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Testimonial;
use Filament\Widgets\ChartWidget;

class TestimonialsByRatingChart extends ChartWidget
{
    protected static ?string $heading = 'Testimonials by Rating';

    protected static ?int $sort = 9;

    protected static string $color = 'warning';

    protected function getData(): array
    {
        $groups = Testimonial::query()
            ->selectRaw('rating, COUNT(*) as total')
            ->whereNotNull('rating')
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->get()
            ->pluck('total', 'rating')
            ->toArray();

        // Ensure ratings 5..1 exist (even if 0)
        $labels = [];
        $data = [];
        for ($r = 5; $r >= 1; $r--) {
            $labels[] = (string) $r;
            $data[] = isset($groups[$r]) ? (int) $groups[$r] : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Testimonials',
                    'data' => $data,
                    'backgroundColor' => [
                        '#f97316',
                        '#fb7185',
                        '#f59e0b',
                        '#60a5fa',
                        '#34d399',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
