<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\AreaGuide;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $areaGuides = AreaGuide::all();

        if ($areaGuides->isEmpty()) {
            $this->command->warn('No AreaGuides found. Please seed AreaGuides first.');
            return;
        }

        $projects = [
            [
                'title' => [
                    'en' => 'Luxury Waterfront Villa',
                    'ar' => 'فيلا فاخرة على الواجهة المائية'
                ],
                'slug' => [
                    'en' => 'luxury-waterfront-villa',
                    'ar' => 'فيلا-فاخرة-واجهة-مائية'
                ],
                'description' => [
                    'en' => 'Stunning 5-bedroom villa with private beach access, infinity pool, and panoramic sea views.',
                    'ar' => 'فيلا رائعة بـ 5 غرف نوم مع إمكانية الوصول الخاص للشاطئ وحمام سباحة لامحدود ومناظر بحرية بانورامية.'
                ],
                'short_description' => [
                    'en' => 'Premium waterfront property with exclusive beach access',
                    'ar' => 'عقار فاخر على الواجهة المائية مع إمكانية وصول حصري للشاطئ'
                ],
                'price_aed' => 7500000,
                'area' => 850,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'parking_spaces' => 4,
                'image' => 'https://via.placeholder.com/800x600?text=Luxury+Villa',
                'status' => 'published',
                'is_featured' => true,
                'delivery_date' => now()->addMonths(6),
                'seo_meta' => [
                    'meta_title' => 'Luxury Waterfront Villa - Laguna Life Real Estate',
                    'meta_description' => 'Discover stunning waterfront villas with private beach access and premium amenities.',
                    'meta_keywords' => 'luxury villa, waterfront property, beach house'
                ]
            ],
            [
                'title' => [
                    'en' => 'Modern Downtown Apartment',
                    'ar' => 'شقة حديثة وسط البلد'
                ],
                'slug' => [
                    'en' => 'modern-downtown-apartment',
                    'ar' => 'شقة-حديثة-وسط-البلد'
                ],
                'description' => [
                    'en' => 'Sleek 3-bedroom apartment in the heart of the city with smart home features and stunning skyline views.',
                    'ar' => 'شقة أنيقة بـ 3 غرف نوم في قلب المدينة مع ميزات منزل ذكي ومناظر خط السماء مذهلة.'
                ],
                'short_description' => [
                    'en' => 'Contemporary city apartment with smart home technology',
                    'ar' => 'شقة حديثة بتقنيات المنزل الذكي'
                ],
                'price_aed' => 2800000,
                'area' => 320,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'parking_spaces' => 2,
                'image' => 'https://via.placeholder.com/800x600?text=Modern+Apartment',
                'status' => 'published',
                'is_featured' => true,
                'delivery_date' => now()->addMonths(3),
                'seo_meta' => [
                    'meta_title' => 'Modern Downtown Apartment - Laguna Life',
                    'meta_description' => 'Premium apartments with smart home features in the downtown area.',
                    'meta_keywords' => 'apartment, downtown, smart home'
                ]
            ],
            [
                'title' => [
                    'en' => 'Spacious Family Villa',
                    'ar' => 'فيلا واسعة للعائلة'
                ],
                'slug' => [
                    'en' => 'spacious-family-villa',
                    'ar' => 'فيلا-واسعة-للعائلة'
                ],
                'description' => [
                    'en' => 'Spacious 6-bedroom villa with garden, swimming pool, and close to schools and shopping centers.',
                    'ar' => 'فيلا واسعة بـ 6 غرف نوم مع حديقة وحمام سباحة وقريبة من المدارس ومراكز التسوق.'
                ],
                'short_description' => [
                    'en' => 'Family-friendly villa near schools and amenities',
                    'ar' => 'فيلا عائلية قريبة من المدارس والمرافق'
                ],
                'price_aed' => 5200000,
                'area' => 1200,
                'bedrooms' => 6,
                'bathrooms' => 4,
                'parking_spaces' => 3,
                'image' => 'https://via.placeholder.com/800x600?text=Family+Villa',
                'status' => 'published',
                'is_featured' => false,
                'delivery_date' => now()->addMonths(8),
                'seo_meta' => [
                    'meta_title' => 'Spacious Family Villa - Laguna Life Real Estate',
                    'meta_description' => 'Large family villas with modern amenities near schools and shopping.',
                    'meta_keywords' => 'family villa, spacious home, schools nearby'
                ]
            ],
            [
                'title' => [
                    'en' => 'Penthouse with Terrace',
                    'ar' => 'بنتهاوس مع شرفة'
                ],
                'slug' => [
                    'en' => 'penthouse-with-terrace',
                    'ar' => 'بنتهاوس-مع-شرفة'
                ],
                'description' => [
                    'en' => 'Exclusive penthouse on the top floor with private terrace, jacuzzi, and 360-degree city views.',
                    'ar' => 'بنتهاوس حصري في الطابق العلوي مع شرفة خاصة وجاكوزي ومناظر المدينة 360 درجة.'
                ],
                'short_description' => [
                    'en' => 'Exclusive penthouse with panoramic views',
                    'ar' => 'بنتهاوس حصري مع مناظر بانورامية'
                ],
                'price_aed' => 4500000,
                'area' => 480,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'parking_spaces' => 2,
                'image' => 'https://via.placeholder.com/800x600?text=Penthouse',
                'status' => 'published',
                'is_featured' => true,
                'delivery_date' => now()->addMonths(4),
                'seo_meta' => [
                    'meta_title' => 'Exclusive Penthouse - Laguna Life',
                    'meta_description' => 'Luxury penthouse with private terrace and panoramic city views.',
                    'meta_keywords' => 'penthouse, luxury, city views'
                ]
            ],
            [
                'title' => [
                    'en' => 'Studio Investment Unit',
                    'ar' => 'وحدة استثمارية استوديو'
                ],
                'slug' => [
                    'en' => 'studio-investment-unit',
                    'ar' => 'وحدة-استثمارية-استوديو'
                ],
                'description' => [
                    'en' => 'Compact studio apartment perfect for investors, fully furnished and ready for rental.',
                    'ar' => 'شقة استوديو صغيرة مثالية للمستثمرين، مفروشة بالكامل وجاهزة للإيجار.'
                ],
                'short_description' => [
                    'en' => 'Investment-ready furnished studio',
                    'ar' => 'استوديو مفروش وجاهز للاستثمار'
                ],
                'price_aed' => 950000,
                'area' => 65,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'parking_spaces' => 1,
                'image' => 'https://via.placeholder.com/800x600?text=Studio',
                'status' => 'draft',
                'is_featured' => false,
                'delivery_date' => now()->addMonths(2),
                'seo_meta' => [
                    'meta_title' => 'Studio Investment Unit - Laguna Life',
                    'meta_description' => 'Affordable furnished studio perfect for investors and renters.',
                    'meta_keywords' => 'studio, investment, furnished'
                ]
            ],
        ];

        foreach ($projects as $project) {
            $areaGuide = $areaGuides->random();

            Project::create([
                ...$project,
                'area_guide_id' => $areaGuide->id,
            ]);
        }

        $this->command->info('ProjectSeeder completed successfully. ' . count($projects) . ' projects created.');
    }
}
