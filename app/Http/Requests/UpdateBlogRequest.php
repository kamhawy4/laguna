<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
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
        $blogId = $this->route('blog');

        return [
            // Translatable fields
            'title' => ['sometimes', 'array'],
            'title.en' => ['required_with:title', 'string', 'max:255'],
            'title.ar' => ['required_with:title', 'string', 'max:255'],
            
            'slug' => ['sometimes', 'array'],
            'slug.en' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug->en')->ignore($blogId)],
            'slug.ar' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug->ar')->ignore($blogId)],
            
            'short_description' => ['sometimes', 'array'],
            'short_description.en' => ['nullable', 'string', 'max:500'],
            'short_description.ar' => ['nullable', 'string', 'max:500'],
            
            'content' => ['sometimes', 'array'],
            'content.en' => ['nullable', 'string'],
            'content.ar' => ['nullable', 'string'],
            
            'meta_title' => ['sometimes', 'array'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_title.ar' => ['nullable', 'string', 'max:255'],
            
            'meta_description' => ['sometimes', 'array'],
            'meta_description.en' => ['nullable', 'string', 'max:500'],
            'meta_description.ar' => ['nullable', 'string', 'max:500'],
            
            // Media fields
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max
            'gallery' => ['sometimes', 'array'],
            'gallery.*' => ['nullable', 'string'], // URLs or paths
            
            // Status and visibility
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'published_at' => ['sometimes', 'date'],
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
