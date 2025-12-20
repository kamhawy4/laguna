<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    use Translatable;

    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 100;

    protected static ?string $recordTitleAttribute = 'company_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Company Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TagsInput::make('phone_numbers')
                            ->label('Phone Numbers')
                            ->helperText('Add multiple phone numbers. Example: +971501234567')
                            ->separator(',')
                            ->columnSpan(1),

                        Forms\Components\TagsInput::make('emails')
                            ->label('Email Addresses')
                            ->helperText('Add multiple email addresses. Example: info@company.com')
                            ->separator(',')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Map & Location')
                    ->schema([
                        Forms\Components\TextInput::make('map_embed_url')
                            ->label('Google Map Embed URL')
                            ->url()
                            ->helperText('Paste the Google Maps embed code URL here')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Footer Content')
                    ->schema([
                        Forms\Components\Textarea::make('footer_text')
                            ->label('Footer Text')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Select::make('default_currency')
                            ->label('Default Currency')
                            ->options([
                                'AED' => 'AED - United Arab Emirates Dirham',
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                                'SAR' => 'SAR - Saudi Arabian Riyal',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\Select::make('default_language')
                            ->label('Default Language')
                            ->options([
                                'en' => 'English',
                                'ar' => 'Arabic',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn (Setting $record): string => $record->getTranslation('company_name', app()->getLocale()) ?? ''),

                Tables\Columns\TextColumn::make('default_currency')
                    ->label('Currency')
                    ->sortable(),

                Tables\Columns\TextColumn::make('default_language')
                    ->label('Language')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSetting::route('/'),
        ];
    }
}
