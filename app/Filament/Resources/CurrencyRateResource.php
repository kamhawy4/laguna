<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyRateResource\Pages;
use App\Filament\Resources\CurrencyRateResource\RelationManagers;
use App\Models\CurrencyRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrencyRateResource extends Resource
{
    protected static ?string $model = CurrencyRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 100;

    protected static ?string $recordTitleAttribute = 'currency_code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Currency Information')
                    ->schema([
                        Forms\Components\TextInput::make('currency_code')
                            ->label('Currency Code')
                            ->required()
                            ->maxLength(3)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g., AED, USD, EUR')
                            ->helperText('3-letter ISO currency code')
                            ->afterStateUpdated(fn (callable $set, ?string $state) => $set('currency_code', strtoupper($state))),

                        Forms\Components\TextInput::make('currency_name')
                            ->label('Currency Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Arab Emirates Dirham'),

                        Forms\Components\TextInput::make('symbol')
                            ->label('Currency Symbol')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('e.g., د.إ, $, €'),

                        Forms\Components\TextInput::make('exchange_rate')
                            ->label('Exchange Rate (relative to base)')
                            ->required()
                            ->numeric()
                            ->step(0.0001)
                            ->minValue(0)
                            ->helperText('Rate relative to base currency (base currency should be 1.0)'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_base_currency')
                            ->label('Base Currency')
                            ->helperText('Only one currency can be the base. The base currency has rate of 1.0')
                            ->disabled(fn ($record) => $record && !$record->is_base_currency && CurrencyRate::where('is_base_currency', true)->exists())
                            ->reactive(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Inactive currencies will not appear in API responses'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('currency_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('symbol')
                    ->label('Symbol')
                    ->sortable(),

                Tables\Columns\TextColumn::make('exchange_rate')
                    ->label('Exchange Rate')
                    ->numeric(decimalPlaces: 4)
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_base_currency')
                    ->label('Base')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
                Tables\Filters\TernaryFilter::make('is_base_currency')
                    ->label('Base Currency'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencyRates::route('/'),
            'create' => Pages\CreateCurrencyRate::route('/create'),
            'edit' => Pages\EditCurrencyRate::route('/{record}/edit'),
        ];
    }
}
