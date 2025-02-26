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
        if (Schema::hasTable('mortgage_bank_programs')) {
            return;
        }

        Schema::create('mortgage_bank_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complex_id')->constrained('residential_complexes');
            $table->string('bank_name', 255);
            $table->unsignedFloat('percent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mortgage_bank_programs');
    }
};
