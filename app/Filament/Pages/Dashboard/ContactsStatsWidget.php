<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Contact;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContactsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalContacts = Contact::count();
        $newContacts = Contact::where('status', 'new')->count();
        $contactsLastSevenDays = Contact::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            Stat::make('Total Leads', $totalContacts)
                ->description('All contact submissions')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info')
                ->icon('heroicon-o-envelope'),

            Stat::make('New Leads', $newContacts)
                ->description('Unread messages')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning')
                ->icon('heroicon-o-exclamation-circle'),

            Stat::make('Last 7 Days', $contactsLastSevenDays)
                ->description('Recent submissions')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->icon('heroicon-o-arrow-trending-up'),
        ];
    }
}
