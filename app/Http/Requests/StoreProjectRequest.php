<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Translatable fields
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            
            'slug' => ['nullable', 'array'],
            'slug.en' => ['nullable', 'string', 'max:255', 'unique:projects,slug->en'],
            'slug.ar' => ['nullable', 'string', 'max:255', 'unique:projects,slug->ar'],
            
            'short_description' => ['nullable', 'array'],
            'short_description.en' => ['nullable', 'string', 'max:500'],
            'short_description.ar' => ['nullable', 'string', 'max:500'],
            
            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            
            'overview' => ['nullable', 'array'],
            'overview.en' => ['nullable', 'string'],
            'overview.ar' => ['nullable', 'string'],
            
            'location' => ['nullable', 'array'],
            'location.en' => ['nullable', 'string', 'max:255'],
            'location.ar' => ['nullable', 'string', 'max:255'],
            
            'developer_name' => ['nullable', 'array'],
            'developer_name.en' => ['nullable', 'string', 'max:255'],
            'developer_name.ar' => ['nullable', 'string', 'max:255'],
            
            'developer_info' => ['nullable', 'array'],
            'developer_info.en' => ['nullable', 'string'],
            'developer_info.ar' => ['nullable', 'string'],
            
            'amenities' => ['nullable', 'array'],
            'amenities.en' => ['nullable', 'array'],
            'amenities.ar' => ['nullable', 'array'],
            
            'payment_plan' => ['nullable', 'array'],
            'payment_plan.en' => ['nullable', 'array'],
            'payment_plan.ar' => ['nullable', 'array'],
            
            'meta_title' => ['nullable', 'array'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_title.ar' => ['nullable', 'string', 'max:255'],
            
            'meta_description' => ['nullable', 'array'],
            'meta_description.en' => ['nullable', 'string', 'max:500'],
            'meta_description.ar' => ['nullable', 'string', 'max:500'],
            
            // Media fields
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string'], // URLs or paths
            'floor_plans' => ['nullable', 'array'],
            'floor_plans.*' => ['nullable', 'string'], // URLs or paths
            
            // Pricing (multi-currency)
            'price_aed' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'price_usd' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            'price_eur' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
            
            // Location coordinates
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            
            // Filter fields
            'area' => ['nullable', 'string', 'max:255'],
            'property_type' => [
                'nullable',
                Rule::in(['apartment', 'villa', 'townhouse', 'penthouse', 'studio', 'other'])
            ],
            'delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
            
            // Status and visibility
            'is_featured' => ['nullable', 'boolean'],
            'status' => [
                'nullable',
                Rule::in(['draft', 'published'])
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The project name is required.',
            'name.en.required' => 'The project name in English is required.',
            'name.ar.required' => 'The project name in Arabic is required.',
            'slug.en.unique' => 'The English slug has already been taken.',
            'slug.ar.unique' => 'The Arabic slug has already been taken.',
            'price_aed.numeric' => 'The AED price must be a number.',
            'price_usd.numeric' => 'The USD price must be a number.',
            'price_eur.numeric' => 'The EUR price must be a number.',
            'latitude.between' => 'The latitude must be between -90 and 90.',
            'longitude.between' => 'The longitude must be between -180 and 180.',
            'property_type.in' => 'The property type must be one of: apartment, villa, townhouse, penthouse, studio, other.',
            'status.in' => 'The status must be either draft or published.',
            'delivery_date.after_or_equal' => 'The delivery date must be today or in the future.',
            'featured_image.image' => 'The featured image must be an image file.',
            'featured_image.max' => 'The featured image must not be larger than 5MB.',
        ];
    }
}
