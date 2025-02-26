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
        Schema::create('sprite_image_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained('residential_complexes');
            $table->string('filepath', 512)->unique();
            $table->smallInteger('x');
            $table->smallInteger('y');
            $table->smallInteger('size_x');
            $table->smallInteger('size_y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprite_image_positions');
    }
};
