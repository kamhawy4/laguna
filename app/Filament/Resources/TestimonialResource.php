<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    use Translatable;

    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Client Information')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->label('Client Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('client_title')
                            ->label('Client Title/Position')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Testimonial')
                    ->schema([
                        Forms\Components\RichEditor::make('testimonial')
                            ->label('Testimonial Text')
                            ->required()
                            ->minLength(10)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Forms\Components\Section::make('Media & Rating')
                    ->schema([
                        Forms\Components\FileUpload::make('client_image')
                            ->label('Client Image')
                            ->image()
                            ->maxSize(2048)
                            ->directory('testimonials')
                            ->columnSpan(1),

                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '⭐ (1 Star)',
                                2 => '⭐⭐ (2 Stars)',
                                3 => '⭐⭐⭐ (3 Stars)',
                                4 => '⭐⭐⭐⭐ (4 Stars)',
                                5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                            ])
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL (Optional)')
                            ->url()
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Settings')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('client_image')
                    ->label('Image')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(fn (Testimonial $record): string => $record->getTranslation('client_name', app()->getLocale()) ?? ''),

                Tables\Columns\TextColumn::make('client_title')
                    ->label('Position')
                    ->limit(40)
                    ->getStateUsing(fn (Testimonial $record): string => $record->getTranslation('client_title', app()->getLocale()) ?? ''),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ])
                    ->sortable(),

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
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '⭐ (1 Star)',
                        2 => '⭐⭐ (2 Stars)',
                        3 => '⭐⭐⭐ (3 Stars)',
                        4 => '⭐⭐⭐⭐ (4 Stars)',
                        5 => '⭐⭐⭐⭐⭐ (5 Stars)',
                    ]),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
