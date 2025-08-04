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
        Schema::table('user_favorite_plans', function (Blueprint $table) {
            $table->unique('offer_id');
        });

        Schema::table('user_favorite_buildings', function (Blueprint $table) {
            $table->unique('complex_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_favorite_plans', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
        });

        Schema::table('user_favorite_buildings', function (Blueprint $table) {
            $table->dropForeign(['complex_code']);
        });

        Schema::table('user_favorite_plans', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropUnique(['offer_id']);
            Schema::enableForeignKeyConstraints();
        });

        Schema::table('user_favorite_buildings', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropUnique(['complex_code']);
            Schema::enableForeignKeyConstraints();
        });

        Schema::table('user_favorite_plans', function (Blueprint $table) {
            $table->foreign('offer_id')->references('offer_id')->on('apartments');
        });

        Schema::table('user_favorite_buildings', function (Blueprint $table) {
            $table->foreign('complex_code')->references('code')->on('residential_complexes');
        });
    }
};
