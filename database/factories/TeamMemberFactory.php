<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->name(),
                'ar' => $this->faker->name() . ' (AR)',
            ],
            'job_title' => [
                'en' => $this->faker->jobTitle(),
                'ar' => $this->faker->jobTitle() . ' (AR)',
            ],
            'bio' => [
                'en' => $this->faker->paragraphs(2, true),
                'ar' => $this->faker->paragraphs(2, true) . ' (AR)',
            ],
            'image' => null,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'linkedin_url' => 'https://linkedin.com/in/' . $this->faker->slug(),
            'seo_meta' => [
                'meta_title' => null,
                'meta_description' => null,
            ],
            'order' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}
