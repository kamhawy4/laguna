<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Blog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBlogs = Blog::count();
        $publishedBlogs = Blog::where('status', 'published')->count();
        $featuredBlogs = Blog::where('is_featured', true)->count();

        return [
            Stat::make('Total Blogs', $totalBlogs)
                ->description('All blog posts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->icon('heroicon-o-document-text'),

            Stat::make('Published', $publishedBlogs)
                ->description('Active articles')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Featured', $featuredBlogs)
                ->description('Highlighted posts')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary')
                ->icon('heroicon-o-star'),
        ];
    }
}
