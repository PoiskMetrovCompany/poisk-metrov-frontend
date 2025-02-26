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
        Schema::create('residential_complex_category_pivots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complex_id')->constrained('residential_complexes');
            $table->foreignId('category_id')->constrained('residential_complex_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residential_complex_category_pivots');
    }
};
