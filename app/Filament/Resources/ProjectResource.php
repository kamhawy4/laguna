<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    use Translatable;

    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Projects';

    protected static ?string $modelLabel = 'Project';

    protected static ?string $pluralModelLabel = 'Projects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Project Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from name if left empty'),

                        Forms\Components\Textarea::make('short_description')
                            ->label('Short Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('overview')
                            ->label('Project Overview')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Location & Developer')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('area')
                            ->label('Area')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->minValue(-90)
                            ->maxValue(90),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->minValue(-180)
                            ->maxValue(180),

                        Forms\Components\TextInput::make('developer_name')
                            ->label('Developer Name')
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('developer_info')
                            ->label('Developer Information')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pricing & Area (Base Currency: AED)')
                    ->schema([
                        Forms\Components\TextInput::make('price_aed')
                            ->label('Price (AED)')
                            ->numeric()
                            ->prefix('AED')
                            ->minValue(0)
                            ->step(0.01)
                            ->placeholder('0.00')
                            ->helperText('Enter the project price in AED (base currency). All other currency conversions are calculated automatically based on exchange rates managed in Currency Rates settings.'),

                        Forms\Components\TextInput::make('area')
                            ->label('Area (sqm)')
                            ->numeric()
                            ->suffix('sqm')
                            ->minValue(0)
                            ->step(0.01)
                            ->placeholder('0.00')
                            ->helperText('Enter the project area in square meters (sqm). Area conversions to sqft happen automatically in the API.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Project Details')
                    ->schema([
                        Forms\Components\Select::make('property_type')
                            ->label('Property Type')
                            ->options([
                                'apartment' => 'Apartment',
                                'villa' => 'Villa',
                                'townhouse' => 'Townhouse',
                                'penthouse' => 'Penthouse',
                                'studio' => 'Studio',
                                'other' => 'Other',
                            ]),

                        Forms\Components\DatePicker::make('delivery_date')
                            ->label('Delivery Date'),

                        Forms\Components\KeyValue::make('amenities')
                            ->label('Amenities')
                            ->keyLabel('Amenity')
                            ->valueLabel('Description')
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('payment_plan')
                            ->label('Payment Plan')
                            ->keyLabel('Installment')
                            ->valueLabel('Details')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('projects/featured')
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('gallery')
                            ->label('Gallery')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Image/Video')
                                    ->image()
                                    ->directory('projects/gallery')
                                    ->maxSize(10240),
                            ])
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('floor_plans')
                            ->label('Floor Plans')
                            ->schema([
                                Forms\Components\FileUpload::make('file')
                                    ->label('Floor Plan')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->directory('projects/floor-plans')
                                    ->maxSize(10240),
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

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
                            ->label('Featured Project')
                            ->default(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn (Project $record): string => $record->getTranslation('name', app()->getLocale()) ?? ''),

                Tables\Columns\TextColumn::make('area')
                    ->label('Area')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('property_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'apartment' => 'info',
                        'villa' => 'success',
                        'townhouse' => 'warning',
                        'penthouse' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('price_aed')
                    ->label('Price (AED)')
                    ->money('AED')
                    ->sortable(),

                Tables\Columns\TextColumn::make('delivery_date')
                    ->label('Delivery Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),

                Tables\Filters\SelectFilter::make('property_type')
                    ->label('Property Type')
                    ->options([
                        'apartment' => 'Apartment',
                        'villa' => 'Villa',
                        'townhouse' => 'Townhouse',
                        'penthouse' => 'Penthouse',
                        'studio' => 'Studio',
                        'other' => 'Other',
                    ]),

                Tables\Filters\Filter::make('delivery_date')
                    ->form([
                        Forms\Components\DatePicker::make('delivery_from')
                            ->label('Delivery From'),
                        Forms\Components\DatePicker::make('delivery_to')
                            ->label('Delivery To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['delivery_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('delivery_date', '>=', $date),
                            )
                            ->when(
                                $data['delivery_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('delivery_date', '<=', $date),
                            );
                    }),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
