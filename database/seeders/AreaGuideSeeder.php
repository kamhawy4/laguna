<?php

namespace Database\Seeders;

use App\Models\AreaGuide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AreaGuide::factory()->count(20)->create();
    }
}
