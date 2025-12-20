<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'phone_numbers' => $this->phone_numbers,
            'emails' => $this->emails,
            'address' => $this->getTranslation('address', $locale),
            'company_name' => $this->getTranslation('company_name', $locale),
            'footer_text' => $this->getTranslation('footer_text', $locale),
            'map_embed_url' => $this->map_embed_url,
            'default_currency' => $this->default_currency,
            'default_language' => $this->default_language,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
