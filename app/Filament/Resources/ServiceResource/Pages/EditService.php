<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditService extends EditRecord
{
    use Translatable;

    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->flattenTranslatableData($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
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
            // Get existing record to preserve data from other locales
            $record = $this->getRecord();

            // Flatten from Filament format to Spatie format
            $flattened = [];

            foreach ($translatableFields as $field) {
                $flattened[$field] = [];

                // Get existing translations
                if ($record && isset($record->$field)) {
                    $existing = is_array($record->$field) ? $record->$field : json_decode($record->$field, true) ?? [];
                    $flattened[$field] = $existing;
                }

                // Merge new translations from all locales
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
