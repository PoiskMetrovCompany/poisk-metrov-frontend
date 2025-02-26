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
        Schema::create('residential_complex_apartment_specifics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('residential_complexes');
            $table->unsignedBigInteger('starting_price');
            $table->unsignedFloat('starting_area');
            $table->smallInteger('count');
            $table->string('display_name', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residential_complex_apartment_specifics');
    }
};
