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
        Schema::create('mortgage_program_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortgage_id')->constrained('mortgages');
            $table->foreignId('program_id')->constrained('mortgage_programs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mortgage_program_pivot');
    }
};
