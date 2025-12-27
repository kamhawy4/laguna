<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    use Translatable;

    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Service Title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Textarea::make('short_description')
                            ->label('Short Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->required()
                            ->minLength(20),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media & Icon')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Service Image')
                            ->image()
                            ->directory('services')
                            ->maxSize(2048),

                        Forms\Components\TextInput::make('icon')
                            ->label('Icon Class')
                            ->placeholder('e.g., fas fa-cogs'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pricing (Base Currency: AED)')
                    ->schema([
                        Forms\Components\TextInput::make('price_aed')
                            ->label('Price (AED)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->placeholder('0.00')
                            ->helperText('Enter the service price in AED. This is the base currency. All other currency conversions are calculated automatically based on exchange rates.'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('SEO Metadata')
                    ->schema([
                        Forms\Components\TextInput::make('seo_meta.meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('seo_meta.meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('seo_meta.meta_keywords')
                            ->label('Meta Keywords')
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Forms\Components\Section::make('Status & Settings')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Service')
                            ->default(false),

                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->size(40),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->getStateUsing(fn (Service $record) =>
                    $record->getTranslation('title', app()->getLocale())
                    ),

                Tables\Columns\TextColumn::make('short_description')
                    ->label('Description')
                    ->limit(50)
                    ->getStateUsing(fn (Service $record) =>
                    $record->getTranslation('short_description', app()->getLocale())
                    ),

                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->limit(30),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('order')
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
