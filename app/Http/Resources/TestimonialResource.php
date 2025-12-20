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
        return [
            'id' => $this->id,
            'client_name' => $this->client_name,
            'client_title' => $this->client_title,
            'testimonial' => $this->testimonial,
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
