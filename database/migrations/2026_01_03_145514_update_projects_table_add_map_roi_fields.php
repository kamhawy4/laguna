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
            // Add new fields
            $table->text('map_embed')->nullable()->after('area');
            $table->decimal('roi', 5, 2)->nullable()->after('map_embed');
            
            // Remove old fields
            if (Schema::hasColumn('projects', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('projects', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'map_embed')) {
                $table->dropColumn('map_embed');
            }
            if (Schema::hasColumn('projects', 'roi')) {
                $table->dropColumn('roi');
            }
            
            // Restore old fields if needed
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        });
    }
};
