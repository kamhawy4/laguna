<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
     * Flatten Filament Translatable concern data structure.
     */
    protected function prepareForValidation(): void
    {
        $this->flattenTranslatableData();
    }

    /**
     * Flatten translatable data from Filament's structure.
     * Filament sends: {'en': {...fields}, 'ar': {...fields}}
     * We need: {'title': {'en': '...', 'ar': '...'}, ...}
     */
    private function flattenTranslatableData(): void
    {
        $translatableFields = ['title', 'slug', 'short_description', 'description', 'meta_title', 'meta_description'];
        $locales = ['en', 'ar'];
        $flattened = [];

        // Check if data is in Filament format (locale as key)
        $filamentFormat = false;
        foreach ($locales as $locale) {
            if ($this->has($locale) && is_array($this->input($locale))) {
                $filamentFormat = true;
                break;
            }
        }

        if ($filamentFormat) {
            // Convert from Filament format: {'en': {...}, 'ar': {...}}
            // To: {'title': {'en': '...', 'ar': '...'}, ...}
            foreach ($translatableFields as $field) {
                $flattened[$field] = [];
                foreach ($locales as $locale) {
                    $localeData = $this->input($locale, []);
                    if (isset($localeData[$field])) {
                        $flattened[$field][$locale] = $localeData[$field];
                    }
                }
            }

            // Merge with other non-translatable fields
            $all = $this->all();
            foreach ($locales as $locale) {
                unset($all[$locale]);
            }

            $this->merge(array_merge($flattened, $all));
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Translatable fields
            'title' => ['sometimes', 'array'],
            'title.en' => ['required_with:title', 'string', 'max:255'],
            'title.ar' => ['required_with:title', 'string', 'max:255'],

            'slug' => ['sometimes', 'array'],
            'slug.en' => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug->en')->ignore($this->id)],
            'slug.ar' => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug->ar')->ignore($this->id)],

            'short_description' => ['sometimes', 'array'],
            'short_description.en' => ['required_with:short_description', 'string', 'max:500'],
            'short_description.ar' => ['required_with:short_description', 'string', 'max:500'],

            'description' => ['sometimes', 'array'],
            'description.en' => ['required_with:description', 'string', 'min:20'],
            'description.ar' => ['required_with:description', 'string', 'min:20'],

            'meta_title' => ['sometimes', 'array'],
            'meta_title.en' => ['nullable', 'string', 'max:255'],
            'meta_title.ar' => ['nullable', 'string', 'max:255'],

            'meta_description' => ['sometimes', 'array'],
            'meta_description.en' => ['nullable', 'string', 'max:500'],
            'meta_description.ar' => ['nullable', 'string', 'max:500'],

            // Media fields
            'icon' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max

            // Status and visibility
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['draft', 'published'])],
            'order' => ['nullable', 'integer', 'min:0'],
            'seo_meta' => ['nullable', 'array'],
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
            'short_description.en' => 'English Short Description',
            'short_description.ar' => 'Arabic Short Description',
            'description.en' => 'English Description',
            'description.ar' => 'Arabic Description',
            'meta_title.en' => 'English Meta Title',
            'meta_title.ar' => 'Arabic Meta Title',
            'meta_description.en' => 'English Meta Description',
            'meta_description.ar' => 'Arabic Meta Description',
        ];
    }
}
