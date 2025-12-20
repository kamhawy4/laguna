<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialMediaLinkRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'platform' => ['sometimes', 'in:facebook,instagram,linkedin,twitter,youtube,tiktok'],
            'label' => ['nullable', 'array'],
            'label.en' => ['nullable', 'string', 'max:255'],
            'label.ar' => ['nullable', 'string', 'max:255'],
            'url' => ['sometimes', 'url', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'platform.in' => 'Platform must be one of: facebook, instagram, linkedin, twitter, youtube, tiktok',
            'url.url' => 'URL must be a valid URL',
        ];
    }
}
