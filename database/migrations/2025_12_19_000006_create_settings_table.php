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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('phone_numbers')->nullable();
            $table->json('emails')->nullable();
            $table->json('address')->nullable();
            $table->json('company_name')->nullable();
            $table->json('footer_text')->nullable();
            $table->string('map_embed_url')->nullable();
            $table->string('default_currency')->default('AED');
            $table->string('default_language')->default('en');
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
