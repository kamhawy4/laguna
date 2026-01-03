<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Sender Name')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->disabled()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Message Content')
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Additional Info')
                    ->schema([
                        Forms\Components\TextInput::make('ip_address')
                            ->label('Sender IP Address')
                            ->disabled()
                            ->columnSpan(1),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'new' => 'New',
                                'read' => 'Read',
                                'closed' => 'Closed',
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('From')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->label('Message Preview')
                    ->limit(60)
                    ->wrap(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'new',
                        'info' => 'read',
                        'success' => 'closed',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListContacts::route('/'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}
