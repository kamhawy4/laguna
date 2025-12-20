<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
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
        $translatableFields = ['address', 'company_name', 'footer_text'];

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
            'phone_numbers' => ['sometimes', 'array'],
            'phone_numbers.*' => ['string', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'emails' => ['sometimes', 'array'],
            'emails.*' => ['email'],
            'address' => ['sometimes', 'array', 'min:2'],
            'address.en' => ['required_with:address', 'string', 'max:500'],
            'address.ar' => ['required_with:address', 'string', 'max:500'],
            'company_name' => ['sometimes', 'array', 'min:2'],
            'company_name.en' => ['required_with:company_name', 'string', 'max:255'],
            'company_name.ar' => ['required_with:company_name', 'string', 'max:255'],
            'footer_text' => ['sometimes', 'array', 'min:2'],
            'footer_text.en' => ['required_with:footer_text', 'string', 'max:500'],
            'footer_text.ar' => ['required_with:footer_text', 'string', 'max:500'],
            'map_embed_url' => ['nullable', 'url'],
            'default_currency' => ['in:AED,USD,EUR,GBP,SAR'],
            'default_language' => ['in:en,ar'],
            'status' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone_numbers.*.regex' => 'Phone number must be a valid format',
            'emails.*.email' => 'Email address must be valid',
            'address.min' => 'Address must be provided in both languages',
            'company_name.min' => 'Company name must be provided in both languages',
            'footer_text.min' => 'Footer text must be provided in both languages',
        ];
    }
}
