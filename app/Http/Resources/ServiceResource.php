<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'slug' => $this->getTranslation('slug', $locale),
            'short_description' => $this->getTranslation('short_description', $locale),
            'description' => $this->getTranslation('description', $locale),
            'icon' => $this->icon,
            'image' => $this->image,
            'is_featured' => (bool) $this->is_featured,
            'order' => $this->order,
            'status' => $this->status,
            'meta_title' => $this->getTranslation('meta_title', $locale),
            'meta_description' => $this->getTranslation('meta_description', $locale),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
