<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'title' => $this->getTranslation('title', $locale) ?? '',
            'slug' => $this->getTranslation('slug', $locale) ?? '',
            'short_description' => $this->getTranslation('short_description', $locale),
            'content' => $this->getTranslation('content', $locale),
            'meta_title' => $this->getTranslation('meta_title', $locale),
            'meta_description' => $this->getTranslation('meta_description', $locale),
            'featured_image' => $this->featured_image,
            'gallery' => $this->gallery ?? [],
            'is_featured' => $this->is_featured ?? false,
            'status' => $this->status ?? 'draft',
            'sort_order' => $this->sort_order ?? 0,
            'published_at' => $this->published_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
