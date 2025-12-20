<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialMediaLinkResource\Pages;
use App\Models\SocialMediaLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialMediaLinkResource extends Resource
{
    protected static ?string $model = SocialMediaLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Social Media Link Information')
                    ->schema([
                        Forms\Components\Select::make('platform')
                            ->label('Platform')
                            ->options([
                                'facebook' => 'Facebook',
                                'instagram' => 'Instagram',
                                'linkedin' => 'LinkedIn',
                                'twitter' => 'Twitter/X',
                                'youtube' => 'YouTube',
                                'tiktok' => 'TikTok',
                            ])
                            ->required()
                            ->reactive()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required()
                            ->maxLength(2000)
                            ->placeholder('https://example.com')
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\Tabs::make('Translations')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('English')
                                    ->icon('heroicon-m-language')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->label('Label (Optional)')
                                            ->maxLength(255)
                                            ->placeholder('e.g., Follow us on Facebook')
                                            ->extraInputAttributes(['data-locale' => 'en']),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Arabic')
                                    ->icon('heroicon-m-language')
                                    ->schema([
                                        Forms\Components\TextInput::make('label_ar')
                                            ->label('Label (Optional)')
                                            ->maxLength(255)
                                            ->placeholder('مثلا، تابعنا على فيسبوك')
                                            ->extraInputAttributes(['data-locale' => 'ar']),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                Forms\Components\Section::make('Icon & Ordering')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('e.g., fab fa-facebook')
                            ->helperText('Font Awesome class name')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'facebook' => 'blue',
                        'instagram' => 'pink',
                        'linkedin' => 'sky',
                        'twitter' => 'cyan',
                        'youtube' => 'red',
                        'tiktok' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->limit(30),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->url(fn ($record) => $record->url, true)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->numeric()
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
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                        'linkedin' => 'LinkedIn',
                        'twitter' => 'Twitter/X',
                        'youtube' => 'YouTube',
                        'tiktok' => 'TikTok',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListSocialMediaLinks::route('/'),
            'create' => Pages\CreateSocialMediaLink::route('/create'),
            'edit' => Pages\EditSocialMediaLink::route('/{record}/edit'),
        ];
    }
}
