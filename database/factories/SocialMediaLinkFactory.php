<?php

namespace Database\Factories;

use App\Models\SocialMediaLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialMediaLink>
 */
class SocialMediaLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $platforms = SocialMediaLink::getPlatforms();
        $platformsWithIcons = SocialMediaLink::getPlatformsWithIcons();
        $platform = $this->faker->randomElement($platforms);

        return [
            'platform' => $platform,
            'label' => [
                'en' => ucfirst($platform),
                'ar' => ucfirst($platform),
            ],
            'url' => match ($platform) {
                'facebook' => 'https://facebook.com/yourpage',
                'instagram' => 'https://instagram.com/yourprofile',
                'linkedin' => 'https://linkedin.com/company/yourcompany',
                'twitter' => 'https://twitter.com/yourhandle',
                'youtube' => 'https://youtube.com/@yourchannel',
                'tiktok' => 'https://tiktok.com/@yourprofile',
                default => 'https://example.com',
            },
            'icon' => $platformsWithIcons[$platform],
            'order' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(90), // 90% active
        ];
    }
}
