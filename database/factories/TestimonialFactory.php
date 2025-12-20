<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition()
    {
        return [
            'client_name' => [
                'en' => $this->faker->name(),
                'ar' => $this->faker->name() . ' (AR)',
            ],
            'client_title' => [
                'en' => $this->faker->randomElement(['Investor', 'Buyer', 'Seller', 'Developer', 'Client']),
                'ar' => $this->faker->randomElement(['Investor', 'Buyer', 'Seller', 'Developer', 'Client']) . ' (AR)',
            ],
            'testimonial' => [
                'en' => $this->faker->paragraphs(2, true),
                'ar' => $this->faker->paragraphs(2, true) . ' (AR)',
            ],
            'rating' => $this->faker->numberBetween(1, 5),
            'client_image' => null,
            'video_url' => null,
            'is_featured' => $this->faker->boolean(20),
            'order' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}
