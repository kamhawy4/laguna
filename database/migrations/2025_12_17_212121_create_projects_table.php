<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // Translatable fields (stored as JSON for Spatie Translatable)
            $table->json('name'); // Project name/title
            $table->json('slug'); // SEO-friendly URL slug
            $table->json('short_description')->nullable(); // Short description for cards
            $table->json('description')->nullable(); // Full description
            $table->json('overview')->nullable(); // Project overview section
            $table->json('location')->nullable(); // Location/address
            $table->json('developer_name')->nullable(); // Developer name
            $table->json('developer_info')->nullable(); // Developer information
            $table->json('amenities')->nullable(); // Amenities list (JSON array)
            $table->json('payment_plan')->nullable(); // Payment plan details (JSON)
            $table->json('meta_title')->nullable(); // SEO meta title
            $table->json('meta_description')->nullable(); // SEO meta description
            
            // Media fields
            $table->string('featured_image')->nullable(); // Featured image path
            $table->json('gallery')->nullable(); // Gallery images/videos (JSON array)
            $table->json('floor_plans')->nullable(); // Floor plans (PDF/images) (JSON array)
            
            // Pricing (multi-currency: AED, USD, EUR)
            $table->decimal('price_aed', 15, 2)->nullable();
            $table->decimal('price_usd', 15, 2)->nullable();
            $table->decimal('price_eur', 15, 2)->nullable();
            
            // Location coordinates for map
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Filter fields
            $table->string('area')->nullable(); // Area name (can be relationship later)
            $table->enum('property_type', ['apartment', 'villa', 'townhouse', 'penthouse', 'studio', 'other'])->nullable();
            $table->date('delivery_date')->nullable(); // Expected delivery date
            
            // Status and visibility
            $table->boolean('is_featured')->default(false); // Featured on homepage
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->integer('sort_order')->default(0); // For custom ordering
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('status');
            $table->index('is_featured');
            $table->index('area');
            $table->index('property_type');
            $table->index('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
