<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Filament\Actions;

class CreateTestimonial extends CreateRecord
{
    use Translatable;

    protected static string $resource = TestimonialResource::class;


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
            // Flatten from Filament format to Spatie format
            $flattened = [];
            
            foreach ($translatableFields as $field) {
                $flattened[$field] = [];
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

