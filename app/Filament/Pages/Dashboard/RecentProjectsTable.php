<?php

namespace App\Filament\Pages\Dashboard;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentProjectsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Projects';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Project::query()
                    ->with('area')
                    ->latest('created_at')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Project Name')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('area.name')
                    ->label('Area')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'draft',
                        'success' => 'published',
                    ])
                    ->sortable(),

                TextColumn::make('delivery_date')
                    ->label('Delivery Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
