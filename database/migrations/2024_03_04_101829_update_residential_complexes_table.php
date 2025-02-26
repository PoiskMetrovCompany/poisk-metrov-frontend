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
            $table->string('parking', 255)->nullable()->change();
            $table->string('amenities', 255)->nullable()->change();
            $table->string('panorama', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('residential_complexes')) {
            return;
        }

        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->string('parking', 255)->nullable(false)->change();
            $table->string('amenities', 255)->nullable(false)->change();
            $table->string('panorama', 255)->nullable(false)->change();
        });
    }
};
