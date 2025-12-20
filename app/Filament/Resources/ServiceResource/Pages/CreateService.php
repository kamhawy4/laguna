<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateService extends CreateRecord
{
    use Translatable;

    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->flattenTranslatableData($data);
    }

    /**
     * Flatten Filament's locale-wrapped data into Spatie format.
     * Filament sends: {'en': {fields...}, 'ar': {fields...}}
     * Spatie expects: {'title': {'en': '...', 'ar': '...'}, ...}
     */
    private function flattenTranslatableData(array $data): array
    {
        $locales = config('app.available_locales', ['en', 'ar']);
        $translatableFields = ['title', 'slug', 'short_description', 'description', 'meta_title', 'meta_description'];

        // Check if data is in Filament format (locale keys at top level)
        $hasLocaleKeys = false;
        foreach ($locales as $locale) {
            if (isset($data[$locale]) && is_array($data[$locale])) {
                $hasLocaleKeys = true;
                break;
            }
        }

        if ($hasLocaleKeys) {
            // Flatten from Filament format to Spatie format
            $flattened = [];

            foreach ($translatableFields as $field) {
                $flattened[$field] = [];
                foreach ($locales as $locale) {
                    if (isset($data[$locale][$field])) {
                        $flattened[$field][$locale] = $data[$locale][$field];
                    }
                }
            }

            // Preserve non-translatable fields
            $result = [];
            foreach ($data as $key => $value) {
                if (!in_array($key, $locales)) {
                    $result[$key] = $value;
                }
            }

            return array_merge($result, $flattened);
        }

        return $data;
    }
}
