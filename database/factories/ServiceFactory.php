<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enTitle = $this->faker->words(3, true);
        $arTitle = 'خدمة ' . $this->faker->words(2, true);

        return [
            'title' => [
                'en' => $enTitle,
                'ar' => $arTitle,
            ],
            'slug' => [
                'en' => Str::slug($enTitle),
                'ar' => Str::slug($arTitle),
            ],
            'short_description' => [
                'en' => $this->faker->sentence(10),
                'ar' => $this->faker->sentence(10),
            ],
            'description' => [
                'en' => $this->faker->paragraphs(3, true),
                'ar' => $this->faker->paragraphs(3, true),
            ],
            'icon' => $this->faker->randomElement(['fas fa-cogs', 'fas fa-tools', 'fas fa-rocket', 'fas fa-lightbulb', 'fas fa-chart-line', 'fas fa-shield-alt']),
            'image' => null,
            'is_featured' => $this->faker->boolean(30), // 30% featured
            'order' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'seo_meta' => [
                'meta_title' => $enTitle,
                'meta_description' => $this->faker->sentence(),
                'meta_keywords' => implode(', ', $this->faker->words(5)),
            ],
        ];
    }
}
