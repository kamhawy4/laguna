<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_numbers' => [
                '+971501234567',
                '+971502345678',
            ],
            'emails' => [
                'info@company.com',
                'contact@company.com',
            ],
            'address' => [
                'en' => 'Dubai, UAE',
                'ar' => 'دبي، الإمارات العربية المتحدة',
            ],
            'company_name' => [
                'en' => 'Your Company Name',
                'ar' => 'اسم شركتك',
            ],
            'footer_text' => [
                'en' => 'All rights reserved © ' . date('Y'),
                'ar' => 'جميع الحقوق محفوظة © ' . date('Y'),
            ],
            'map_embed_url' => 'https://maps.google.com/maps?q=Dubai&t=&z=13&ie=UTF8&iwloc=&output=embed',
            'default_currency' => 'AED',
            'default_language' => 'en',
            'status' => true,
        ];
    }
}
