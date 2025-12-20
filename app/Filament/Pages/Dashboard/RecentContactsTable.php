<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Contact;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentContactsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Leads';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contact::query()
                    ->latest('created_at')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(50),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'new',
                        'info' => 'read',
                        'success' => 'closed',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
