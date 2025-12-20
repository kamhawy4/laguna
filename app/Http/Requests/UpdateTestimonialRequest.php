<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->flattenTranslatableData();
    }

    /**
     * Flatten Filament's locale-wrapped data into Spatie format.
     */
    private function flattenTranslatableData(): void
    {
        $locales = config('app.available_locales', ['en', 'ar']);
        $translatableFields = ['client_name', 'client_title', 'testimonial'];

        foreach ($translatableFields as $field) {
            $translations = [];
            foreach ($locales as $locale) {
                if ($this->has("{$locale}.{$field}")) {
                    $translations[$locale] = $this->input("{$locale}.{$field}");
                }
            }
            if (!empty($translations)) {
                $this->merge([$field => $translations]);
            }
        }

        // Remove locale-wrapped keys
        foreach ($locales as $locale) {
            if ($this->has($locale)) {
                $this->request->remove($locale);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_name' => ['sometimes', 'array', 'min:2'],
            'client_name.en' => ['required_with:client_name', 'string', 'max:255'],
            'client_name.ar' => ['required_with:client_name', 'string', 'max:255'],
            'client_title' => ['sometimes', 'array', 'min:2'],
            'client_title.en' => ['required_with:client_title', 'string', 'max:255'],
            'client_title.ar' => ['required_with:client_title', 'string', 'max:255'],
            'testimonial' => ['sometimes', 'array', 'min:2'],
            'testimonial.en' => ['required_with:testimonial', 'string', 'min:10'],
            'testimonial.ar' => ['required_with:testimonial', 'string', 'min:10'],
            'rating' => ['sometimes', 'integer', 'between:1,5'],
            'video_url' => ['nullable', 'url'],
            'client_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_featured' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['in:draft,published'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_name.required_with' => 'Client name is required in both languages',
            'client_name.min' => 'Client name must be provided in both languages',
            'client_title.required_with' => 'Client title is required in both languages',
            'client_title.min' => 'Client title must be provided in both languages',
            'testimonial.required_with' => 'Testimonial is required in both languages',
            'testimonial.min' => 'Testimonial must be provided in both languages',
            'testimonial.*.min' => 'Testimonial must be at least 10 characters',
            'rating.between' => 'Rating must be between 1 and 5',
        ];
    }
}
