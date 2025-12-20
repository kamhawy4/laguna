<?php

namespace App\Filament\Resources\AreaGuideResource\Pages;

use App\Filament\Resources\AreaGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListAreaGuides extends ListRecords
{
    use Translatable;

    protected static string $resource = AreaGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
