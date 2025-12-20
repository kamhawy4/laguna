<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogRequest extends FormRequest
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
            'title' => ['required', 'array'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['required', 'string', 'max:255'],
            
            'slug' => ['nullable', 'array'],
            'slug.en' => ['nullable', 'string', 'max:255', 'unique:blogs,slug->en'],
            'slug.ar' => ['nullable', 'string', 'max:255', 'unique:blogs,slug->ar'],
            
            'short_description' => ['nullable', 'array'],
            'short_description.en' => ['nullable', 'string', 'max:500'],
            'short_description.ar' => ['nullable', 'string', 'max:500'],
            
            'content' => ['nullable', 'array'],
            'content.en' => ['nullable', 'string'],
            'content.ar' => ['nullable', 'string'],
            
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
            
            // Status and visibility
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['draft', 'published'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'title.en' => 'English Title',
            'title.ar' => 'Arabic Title',
            'content.en' => 'English Content',
            'content.ar' => 'Arabic Content',
            'short_description.en' => 'English Short Description',
            'short_description.ar' => 'Arabic Short Description',
        ];
    }
}
