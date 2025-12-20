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
        Schema::create('area_guides', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->json('slug')->nullable();
            $table->json('description')->nullable();
            $table->string('image')->nullable();
            $table->json('seo_meta')->nullable();
            $table->boolean('is_popular')->default(false)->index();
            $table->string('status')->default('draft')->index();
            $table->integer('sort_order')->default(0)->index();
            $table->softDeletes();
            $table->timestamps();
        });

        // Pivot table for area_guides and projects relationship
        Schema::create('area_guide_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_guide_id')->constrained('area_guides')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['area_guide_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_guide_project');
        Schema::dropIfExists('area_guides');
    }
};
