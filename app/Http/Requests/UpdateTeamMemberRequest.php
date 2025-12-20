<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamMemberRequest extends FormRequest
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
        $memberId = $this->route('team_member');

        return [
            // Translatable fields
            'name' => ['sometimes', 'array'],
            'name.en' => ['required_with:name', 'string', 'max:255'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            
            'job_title' => ['sometimes', 'array'],
            'job_title.en' => ['nullable', 'string', 'max:255'],
            'job_title.ar' => ['nullable', 'string', 'max:255'],
            
            'bio' => ['sometimes', 'array'],
            'bio.en' => ['nullable', 'string'],
            'bio.ar' => ['nullable', 'string'],
            
            // Media
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            
            // Contact info
            'email' => ['sometimes', 'email', Rule::unique('team_members', 'email')->ignore($memberId)],
            'phone' => ['sometimes', 'string', 'max:20'],
            'linkedin_url' => ['sometimes', 'url', 'max:255'],
            
            // SEO
            'seo_meta' => ['sometimes', 'array'],
            'seo_meta.meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta.meta_description' => ['nullable', 'string', 'max:500'],
            
            // Status and ordering
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'order' => ['sometimes', 'integer', 'min:0'],
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
