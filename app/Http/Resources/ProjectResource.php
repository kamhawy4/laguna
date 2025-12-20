<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'short_description' => $this->getTranslation('short_description', $locale),
            'description' => $this->getTranslation('description', $locale),
            'overview' => $this->getTranslation('overview', $locale),
            'location' => $this->getTranslation('location', $locale),
            'developer_name' => $this->getTranslation('developer_name', $locale),
            'developer_info' => $this->getTranslation('developer_info', $locale),
            'amenities' => $this->getTranslation('amenities', $locale) ?? [],
            'payment_plan' => $this->getTranslation('payment_plan', $locale) ?? [],
            'meta_title' => $this->getTranslation('meta_title', $locale),
            'meta_description' => $this->getTranslation('meta_description', $locale),
            'featured_image' => $this->featured_image,
            'gallery' => $this->gallery ?? [],
            'floor_plans' => $this->floor_plans ?? [],
            'pricing' => [
                'aed' => $this->price_aed,
                'usd' => $this->price_usd,
                'eur' => $this->price_eur,
            ],
            'coordinates' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'area' => $this->area,
            'property_type' => $this->property_type,
            'delivery_date' => $this->delivery_date?->format('Y-m-d'),
            'is_featured' => $this->is_featured ?? false,
            'status' => $this->status ?? 'draft',
            'sort_order' => $this->sort_order ?? 0,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
