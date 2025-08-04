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
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->dropColumn('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->string('amenities', 255);
        });
    }
};
