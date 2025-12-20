<?php

namespace App\Filament\Resources\SocialMediaLinkResource\Pages;

use App\Filament\Resources\SocialMediaLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSocialMediaLink extends CreateRecord
{
    protected static string $resource = SocialMediaLinkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
