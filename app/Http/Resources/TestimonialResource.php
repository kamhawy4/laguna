<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'client_name' => $this->getTranslation('client_name', $locale),
            'client_title' => $this->getTranslation('client_title', $locale),
            'testimonial' => $this->getTranslation('testimonial', $locale),
            'rating' => $this->rating,
            'client_image' => $this->client_image,
            'video_url' => $this->video_url,
            'is_featured' => (bool) $this->is_featured,
            'status' => $this->status,
            'order' => $this->order,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
