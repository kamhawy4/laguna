<?php

namespace App\Filament\Resources\AreaGuideResource\Pages;

use App\Filament\Resources\AreaGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;
use Illuminate\Support\Str;

class EditAreaGuide extends EditRecord
{
    use Translatable;

    protected static string $resource = AreaGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function generateUniqueSlug(
        string $baseSlug,
        string $locale,
        ?int $ignoreId = null
    ): string {
        $slug = $baseSlug;
        $counter = 1;

        while (
        \App\Models\AreaGuide::where("slug->{$locale}", $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $recordId = $this->record->id;

        // لو الاسم مترجم
        if (isset($data['name']) && is_array($data['name'])) {
            foreach ($data['name'] as $locale => $name) {
                $baseSlug = Str::slug($name);

                $data['slug'][$locale] = $this->generateUniqueSlug(
                    $baseSlug,
                    $locale,
                    $recordId // 👈 مهم
                );
            }
        }

        // لو الاسم String
        if (isset($data['name']) && is_string($data['name'])) {
            $baseSlug = Str::slug($data['name']);

            $data['slug'] = $this->generateUniqueSlug(
                $baseSlug,
                app()->getLocale(),
                $recordId
            );
        }

        return $data;
    }
}
