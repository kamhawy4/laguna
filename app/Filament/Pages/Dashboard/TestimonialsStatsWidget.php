<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestimonialsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTestimonials = Testimonial::count();
        $featuredTestimonials = Testimonial::where('is_featured', true)->count();
        $ratingsAverage = Testimonial::avg('rating') ?? 0;

        return [
            Stat::make('Total Testimonials', $totalTestimonials)
                ->description('All testimonials')
                ->descriptionIcon('heroicon-m-chat-bubble-left')
                ->color('info')
                ->icon('heroicon-o-chat-bubble-left'),

            Stat::make('Featured', $featuredTestimonials)
                ->description('Highlighted reviews')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger')
                ->icon('heroicon-o-heart'),

            Stat::make('Avg Rating', number_format($ratingsAverage, 1) . '★')
                ->description('Customer satisfaction')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary')
                ->icon('heroicon-o-star'),
        ];
    }
}
