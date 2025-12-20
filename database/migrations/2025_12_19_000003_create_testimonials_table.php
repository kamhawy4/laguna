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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->json('client_name')->nullable();
            $table->json('client_title')->nullable();
            $table->json('testimonial')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('client_image')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->integer('order')->default(0)->index();
            $table->string('status')->default('draft')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
