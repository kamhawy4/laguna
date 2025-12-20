<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAreaGuideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $guideId = $this->route('area_guide');

        return [
            // Translatable fields
            'name' => ['sometimes', 'array'],
            'name.en' => ['required_with:name', 'string', 'max:255'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            
            'slug' => ['sometimes', 'array'],
            'slug.en' => ['nullable', 'string', 'max:255', Rule::unique('area_guides', 'slug->en')->ignore($guideId)],
            'slug.ar' => ['nullable', 'string', 'max:255', Rule::unique('area_guides', 'slug->ar')->ignore($guideId)],
            
            'description' => ['sometimes', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            
            // Media
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            
            // SEO
            'seo_meta' => ['sometimes', 'array'],
            'seo_meta.meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta.meta_description' => ['nullable', 'string', 'max:500'],
            'seo_meta.og' => ['nullable', 'array'],
            
            // Status and visibility
            'is_popular' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            
            // Projects
            'project_ids' => ['sometimes', 'array'],
            'project_ids.*' => ['integer', 'exists:projects,id'],
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'name.en' => 'English Name',
            'name.ar' => 'Arabic Name',
            'description.en' => 'English Description',
            'description.ar' => 'Arabic Description',
        ];
    }
}
