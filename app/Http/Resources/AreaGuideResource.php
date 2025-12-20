<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaGuideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale) ?? '',
            'slug' => $this->getTranslation('slug', $locale) ?? '',
            'description' => $this->getTranslation('description', $locale),
            'image' => $this->image,
            'seo_meta' => $this->seo_meta,
            'is_popular' => $this->is_popular ?? false,
            'status' => $this->status ?? 'draft',
            'sort_order' => $this->sort_order ?? 0,
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
