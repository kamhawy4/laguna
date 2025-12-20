<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class ManageSetting extends EditRecord
{
    use Translatable;

    protected static string $resource = SettingResource::class;

    protected static ?string $title = 'Site Settings';

    public function mount(string|int $record = null): void
    {
        // Get or create the first setting record
        $setting = Setting::first();

        if (!$setting) {
            $setting = Setting::create([
                'company_name' => ['en' => '', 'ar' => ''],
                'address' => ['en' => '', 'ar' => ''],
                'footer_text' => ['en' => '', 'ar' => ''],
            ]);
        }

        // Call parent mount with the setting ID
        parent::mount($setting->id);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->flattenTranslatableData($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Flatten Filament's locale-wrapped data into Spatie format.
     */
    private function flattenTranslatableData(array $data): array
    {
        $locales = config('app.available_locales', ['en', 'ar']);
        $translatableFields = ['address', 'company_name', 'footer_text'];

        foreach ($translatableFields as $field) {
            $translations = [];
            foreach ($locales as $locale) {
                if (isset($data[$locale][$field])) {
                    $translations[$locale] = $data[$locale][$field];
                }
            }
            if (!empty($translations)) {
                $data[$field] = $translations;
            }
        }

        // Remove locale-wrapped keys
        foreach ($locales as $locale) {
            unset($data[$locale]);
        }

        return $data;
    }
}
