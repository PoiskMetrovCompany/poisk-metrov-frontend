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
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('apartment_type', 255)->nullable()->default(null)->change();
            $table->string('renovation', 255)->nullable()->default(null)->change();
            $table->string('balcony', 255)->nullable()->default(null)->change();
            $table->string('bathroom_unit', 255)->nullable()->default(null)->change();
            $table->string('building_materials', 64)->nullable()->default(null)->change();
            $table->string('building_state', 64)->nullable()->default(null)->change();
            $table->string('building_phase', 64)->nullable()->default(null)->change();
            $table->string('building_section', 64)->nullable()->default(null)->change();
            $table->unsignedTinyInteger('ready_quarter')->nullable()->default(null)->change();
            $table->unsignedSmallInteger('built_year')->nullable()->default(null)->change();
            $table->unsignedFloat('ceiling_height')->nullable()->default(null)->change();
            $table->unsignedFloat('living_space')->nullable()->default(null)->change();
            $table->unsignedFloat('kitchen_space')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->string('apartment_type', 255)->nullable(false)->change();
            $table->string('renovation', 255)->nullable(false)->change();
            $table->string('balcony', 255)->nullable(false)->change();
            $table->string('bathroom_unit', 255)->nullable(false)->change();
            $table->string('building_materials', 64)->nullable(false)->change();
            $table->string('building_state', 64)->nullable(false)->change();
            $table->string('building_phase', 64)->nullable(false)->change();
            $table->string('building_section', 64)->nullable(false)->change();
            $table->unsignedTinyInteger('ready_quarter')->nullable(false)->change();
            $table->unsignedSmallInteger('built_year')->nullable(false)->change();
            $table->unsignedFloat('ceiling_height')->nullable(false)->change();
            $table->unsignedFloat('living_space')->nullable(false)->change();
            $table->unsignedFloat('kitchen_space')->nullable(false)->change();
        });
    }
};
