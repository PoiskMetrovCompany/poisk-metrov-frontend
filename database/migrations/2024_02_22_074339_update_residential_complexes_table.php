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
        if (!Schema::hasTable('residential_complexes')) {
            return;
        }
        
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->string('parking', 255);
            $table->string('amenities', 255);
            $table->string('panorama', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->dropColumn(['parking', 'amenities', 'panorama']);
        });
    }
};
