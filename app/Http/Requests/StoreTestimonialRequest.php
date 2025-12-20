<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
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
            'client_name' => ['required', 'array', 'min:2'],
            'client_name.en' => ['required', 'string', 'max:255'],
            'client_name.ar' => ['required', 'string', 'max:255'],
            'client_title' => ['required', 'array', 'min:2'],
            'client_title.en' => ['required', 'string', 'max:255'],
            'client_title.ar' => ['required', 'string', 'max:255'],
            'testimonial' => ['required', 'array', 'min:2'],
            'testimonial.en' => ['required', 'string', 'min:10'],
            'testimonial.ar' => ['required', 'string', 'min:10'],
            'rating' => ['required', 'integer', 'between:1,5'],
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
            'client_name.required' => 'Client name is required in both languages',
            'client_name.min' => 'Client name must be provided in both languages',
            'client_title.required' => 'Client title is required in both languages',
            'client_title.min' => 'Client title must be provided in both languages',
            'testimonial.required' => 'Testimonial is required in both languages',
            'testimonial.min' => 'Testimonial must be provided in both languages',
            'testimonial.*.min' => 'Testimonial must be at least 10 characters',
        ];
    }
}
            'rating.between' => 'Rating must be between 1 and 5',
        ];
    }
}
