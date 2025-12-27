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
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'price_usd')) {
                $table->dropColumn('price_usd');
            }
            if (Schema::hasColumn('projects', 'price_eur')) {
                $table->dropColumn('price_eur');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('price_usd', 15, 2)->nullable();
            $table->decimal('price_eur', 15, 2)->nullable();
        });
    }
};
