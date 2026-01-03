<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Illuminate\Support\Str;

class CreateProject extends CreateRecord
{
    use Translatable;

    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
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
        \App\Models\Project::where("slug->{$locale}", $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // لو الاسم مترجم
        if (isset($data['name']) && is_array($data['name'])) {
            foreach ($data['name'] as $locale => $name) {
                $baseSlug = Str::slug($name);

                $data['slug'][$locale] = $this->generateUniqueSlug(
                    $baseSlug,
                    $locale
                );
            }
        }

        // لو الاسم String (لغة واحدة)
        if (isset($data['name']) && is_string($data['name'])) {
            $baseSlug = Str::slug($data['name']);

            $data['slug'] = $this->generateUniqueSlug(
                $baseSlug,
                app()->getLocale()
            );
        }

        return $data;
    }



}
