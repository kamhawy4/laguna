<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Setting;
use App\Models\SocialMediaLink;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SettingsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $settings = Setting::first();
        $activeSocialLinks = SocialMediaLink::where('is_active', true)->count();

        $defaultLanguage = $settings?->default_language ?? 'en';
        $defaultCurrency = $settings?->default_currency ?? 'AED';

        return [
            Stat::make('Active Social Links', $activeSocialLinks)
                ->description('Connected social media')
                ->descriptionIcon('heroicon-m-link')
                ->color('info')
                ->icon('heroicon-o-link'),

            Stat::make('Default Language', strtoupper($defaultLanguage))
                ->description('System language')
                ->descriptionIcon('heroicon-m-language')
                ->color('primary')
                ->icon('heroicon-o-language'),

            Stat::make('Default Currency', $defaultCurrency)
                ->description('Transaction currency')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
