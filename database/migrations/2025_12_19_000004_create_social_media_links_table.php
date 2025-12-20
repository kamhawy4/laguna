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
        Schema::create('social_media_links', function (Blueprint $table) {
            $table->id();
            $table->enum('platform', ['facebook', 'instagram', 'linkedin', 'twitter', 'youtube', 'tiktok'])->index();
            $table->json('label')->nullable(); // Translatable: {en: "...", ar: "..."}
            $table->string('url');
            $table->string('icon')->default('fab fa-link'); // Font Awesome icon class
            $table->integer('order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_links');
    }
};
