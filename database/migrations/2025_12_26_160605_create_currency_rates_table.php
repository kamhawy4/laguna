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
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code')->unique(); // e.g., AED, USD, EUR
            $table->string('currency_name'); // e.g., Arab Emirates Dirham
            $table->string('symbol'); // e.g., د.إ, $, €
            $table->decimal('exchange_rate', 10, 4); // Exchange rate relative to base currency
            $table->boolean('is_base_currency')->default(false); // Only one base currency
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
