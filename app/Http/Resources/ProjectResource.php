<?php

namespace App\Http\Resources;

use App\Services\AreaUnitConversionService;
use App\Services\CurrencyConversionService;
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
        $currency = $request->currency ?? 'AED';
        $areaUnit = $request->areaUnit ?? 'sqm';

        // Initialize conversion services
        $currencyService = new CurrencyConversionService();
        $areaService = new AreaUnitConversionService();

        // Convert base currency (AED) to requested currency
        $convertedPrice = $currencyService->convertFromBase((float) $this->price_aed, $currency);

        // Convert base area (sqm) to requested unit
        $convertedArea = $areaService->convertFromBase((float) $this->area, $areaUnit);

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
                'currency' => $currency,
                'price' => $convertedPrice,
                'base_price_aed' => (float) $this->price_aed, // For reference
            ],
            'area' => [
                'value' => $convertedArea,
                'unit' => $areaUnit,
                'base_value_sqm' => (float) $this->area, // For reference
            ],
            'map_embed' => $this->map_embed,
            'roi' => (float) ($this->roi ?? 0),
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

