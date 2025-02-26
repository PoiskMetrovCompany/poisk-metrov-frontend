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
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->string('elevator', 64)->nullable();
            $table->string('primary_material', 64)->nullable();
            $table->tinyInteger('floors')->nullable();
            $table->unsignedFloat('primary_ceiling_height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->dropColumn('elevator');
            $table->dropColumn('primary_material');
            $table->dropColumn('floors');
            $table->dropColumn('primary_ceiling_height');
        });
    }
};
