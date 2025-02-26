<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('residential_complexes')) {
            return;
        }

        Schema::table('residential_complexes', function (Blueprint $table) {
            if (Schema::hasColumn('residential_complexes', 'amenities')) {
                $table->string('amenities', 4096)->nullable()->change();
            } else {
                $table->string('amenities', 4096)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->string('amenities', 255)->nullable()->change();
        });
    }
};
