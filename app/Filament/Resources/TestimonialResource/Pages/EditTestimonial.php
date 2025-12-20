<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditTestimonial extends EditRecord
{
    use Translatable;

    protected static string $resource = TestimonialResource::class;

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
     * Spatie expects: {'client_name': {'en': '...', 'ar': '...'}, ...}
     */
    private function flattenTranslatableData(array $data): array
    {
        $locales = config('app.available_locales', ['en', 'ar']);
        $translatableFields = ['client_name', 'client_title', 'testimonial'];

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

                // Update with new locale data
                foreach ($locales as $locale) {
                    if (isset($data[$locale][$field])) {
                        $flattened[$field][$locale] = $data[$locale][$field];
                    }
                }

                // Only include if we have data for at least one locale
                if (empty($flattened[$field])) {
                    unset($flattened[$field]);
                }
            }

            // Get non-translatable fields
            $nonTranslatable = [];
            foreach ($data as $key => $value) {
                if (!in_array($key, $locales) && !in_array($key, $translatableFields)) {
                    $nonTranslatable[$key] = $value;
                }
            }

            return array_merge($flattened, $nonTranslatable);
        }

        return $data;
    }
}
