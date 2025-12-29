<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name' => [
                    'en' => 'Luxury Waterfront Villa',
                    'ar' => 'فيلا فاخرة على الواجهة المائية',
                ],
                'slug' => [
                    'en' => 'luxury-waterfront-villa',
                    'ar' => 'فيلا-فاخرة-على-الواجهة-المائية',
                ],
                'short_description' => [
                    'en' => 'Premium waterfront villa with private beach access.',
                    'ar' => 'فيلا فاخرة على الواجهة المائية مع شاطئ خاص.',
                ],
                'description' => [
                    'en' => 'A stunning luxury villa featuring private beach access, infinity pool, and panoramic sea views.',
                    'ar' => 'فيلا فاخرة تتميز بشاطئ خاص وحمام سباحة لا متناهي وإطلالة بحرية بانورامية.',
                ],
                'overview' => [
                    'en' => 'High-end residential project for luxury living.',
                    'ar' => 'مشروع سكني فاخر لحياة راقية.',
                ],
                'location' => [
                    'en' => 'Palm Jumeirah, Dubai',
                    'ar' => 'نخلة جميرا، دبي',
                ],
                'developer_name' => [
                    'en' => 'Laguna Developments',
                    'ar' => 'لاجونا للتطوير العقاري',
                ],
                'developer_info' => [
                    'en' => 'A leading real estate developer specializing in luxury projects.',
                    'ar' => 'شركة رائدة في تطوير المشاريع العقارية الفاخرة.',
                ],
                'amenities' => [
                    'en' => [
                        'Private Beach' => 'Exclusive beach access',
                        'Infinity Pool' => 'Sea-view pool',
                    ],
                    'ar' => [
                        'شاطئ خاص' => 'وصول حصري للشاطئ',
                        'حمام سباحة لا متناهي' => 'إطلالة بحرية',
                    ],
                ],
                'payment_plan' => [
                    'en' => [
                        'Down Payment' => '20%',
                        'On Handover' => '80%',
                    ],
                    'ar' => [
                        'الدفعة المقدمة' => '20%',
                        'عند الاستلام' => '80%',
                    ],
                ],
                'featured_image' => 'projects/featured/sample.jpg',
                'gallery' => [
                    ['image' => 'projects/gallery/sample1.jpg'],
                    ['image' => 'projects/gallery/sample2.jpg'],
                ],
                'floor_plans' => [
                    ['file' => 'projects/floor-plans/plan1.pdf'],
                ],
                'price_aed' => 7500000,
                'latitude' => 25.11234567,
                'longitude' => 55.13876543,
                'area' => 850,
                'property_type' => 'villa',
                'delivery_date' => now()->addMonths(6),
                'is_featured' => true,
                'status' => 'published',
                'sort_order' => 1,
            ],

            [
                'name' => [
                    'en' => 'Modern Downtown Apartment',
                    'ar' => 'شقة حديثة وسط المدينة',
                ],
                'slug' => [
                    'en' => 'modern-downtown-apartment',
                    'ar' => 'شقة-حديثة-وسط-المدينة',
                ],
                'short_description' => [
                    'en' => 'Contemporary apartment in the heart of the city.',
                    'ar' => 'شقة عصرية في قلب المدينة.',
                ],
                'description' => [
                    'en' => 'Modern apartment with skyline views and smart home features.',
                    'ar' => 'شقة حديثة بإطلالات رائعة وتقنيات منزل ذكي.',
                ],
                'location' => [
                    'en' => 'Downtown Dubai',
                    'ar' => 'وسط مدينة دبي',
                ],
                'price_aed' => 2800000,
                'area' => 320,
                'property_type' => 'apartment',
                'delivery_date' => now()->addMonths(3),
                'is_featured' => false,
                'status' => 'published',
                'sort_order' => 2,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        $this->command->info('Projects seeded successfully.');
    }
}
