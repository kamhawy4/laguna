<?php

namespace App\Filament\Pages;

use App\Models\Blog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SocialMediaLink;
use App\Models\Testimonial;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\ChartWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = 'dashboard';

    public function getWidgets(): array
    {
        return [
            Dashboard\ProjectsStatsWidget::class,
            Dashboard\ContactsStatsWidget::class,
            Dashboard\BlogsStatsWidget::class,
            Dashboard\TestimonialsStatsWidget::class,
            Dashboard\ServicesStatsWidget::class,
            Dashboard\SettingsStatsWidget::class,
            Dashboard\RecentProjectsTable::class,
            Dashboard\RecentContactsTable::class,
            Dashboard\RecentBlogsTable::class,
            Dashboard\RecentTestimonialsTable::class,
            Dashboard\RecentServicesTable::class,
            Dashboard\ContactsLineChart::class,
            Dashboard\ProjectsByAreaChart::class,
            Dashboard\TestimonialsByRatingChart::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'md' => 2,
            'lg' => 3,
        ];
    }
}
