<?php

namespace Database\Factories;

use App\Models\AreaGuide;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AreaGuideFactory extends Factory
{
    protected $model = AreaGuide::class;

    public function definition()
    {
        $name = [
            'en' => $this->faker->city(),
            'ar' => $this->faker->city() . ' (AR)',
        ];

        $slug = collect($name)->map(fn ($n) => Str::slug($n))->toArray();

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => [
                'en' => $this->faker->paragraphs(3, true),
                'ar' => $this->faker->paragraphs(3, true) . ' (AR)',
            ],
            'image' => null,
            'seo_meta' => [
                'meta_title' => $this->faker->sentence(),
                'meta_description' => $this->faker->sentence(15),
                'og' => [
                    'type' => 'article',
                    'image' => null,
                ],
            ],
            'is_popular' => $this->faker->boolean(30),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
