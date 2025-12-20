<?php

namespace App\Filament\Resources\AreaGuideResource\Pages;

use App\Filament\Resources\AreaGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateAreaGuide extends CreateRecord
{
    use Translatable;

    protected static string $resource = AreaGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
