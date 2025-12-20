<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition()
    {
        $title = [
            'en' => $this->faker->sentence(6),
            'ar' => $this->faker->sentence(6) . ' (AR)',
        ];

        $slug = collect($title)->map(fn ($t) => Str::slug($t))->toArray();

        return [
            'title' => $title,
            'slug' => $slug,
            'short_description' => [
                'en' => $this->faker->paragraph(2),
                'ar' => $this->faker->paragraph(2) . ' (AR)',
            ],
            'content' => [
                'en' => $this->faker->paragraphs(4, true),
                'ar' => $this->faker->paragraphs(4, true) . ' (AR)',
            ],
            'featured_image' => null,
            'gallery' => [],
            'meta_title' => null,
            'meta_description' => null,
            'is_featured' => $this->faker->boolean(10),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
