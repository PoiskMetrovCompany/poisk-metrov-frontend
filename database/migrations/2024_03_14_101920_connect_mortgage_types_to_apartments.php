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
        Schema::table('mortgage_types', function (Blueprint $table) {
            DB::table('mortgage_types')->truncate();
            $table->dropConstrainedForeignId('complex_id');
            $table->foreignId('apartment_id')->constrained('apartments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mortgage_types', function (Blueprint $table) {
            DB::table('mortgage_types')->truncate();
            $table->dropConstrainedForeignId('apartment_id');
            $table->foreignId('complex_id')->constrained('residential_complexes');
        });
    }
};
