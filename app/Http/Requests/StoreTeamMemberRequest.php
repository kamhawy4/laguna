<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamMemberRequest extends FormRequest
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
        return [
            // Translatable fields
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            
            'job_title' => ['nullable', 'array'],
            'job_title.en' => ['nullable', 'string', 'max:255'],
            'job_title.ar' => ['nullable', 'string', 'max:255'],
            
            'bio' => ['nullable', 'array'],
            'bio.en' => ['nullable', 'string'],
            'bio.ar' => ['nullable', 'string'],
            
            // Media
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            
            // Contact info
            'email' => ['nullable', 'email', 'unique:team_members,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            
            // SEO
            'seo_meta' => ['nullable', 'array'],
            'seo_meta.meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta.meta_description' => ['nullable', 'string', 'max:500'],
            
            // Status and ordering
            'status' => ['nullable', Rule::in(['draft', 'published'])],
            'order' => ['nullable', 'integer', 'min:0'],
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
            'job_title.en' => 'English Job Title',
            'job_title.ar' => 'Arabic Job Title',
            'bio.en' => 'English Bio',
            'bio.ar' => 'Arabic Bio',
        ];
    }
}
