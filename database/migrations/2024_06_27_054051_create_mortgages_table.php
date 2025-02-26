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
        Schema::create('mortgages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained('banks');
            $table->unsignedBigInteger('original_id');
            $table->unsignedTinyInteger('from_year')->default(1);
            $table->unsignedTinyInteger('to_year')->default(100);
            $table->unsignedBigInteger('from_amount')->default(0);
            $table->unsignedBigInteger('to_amount')->nullable();
            $table->unsignedFloat('min_rate')->default(0);
            $table->unsignedFloat('max_rate')->default(100);
            $table->unsignedFloat('min_initial_fee')->default(0);
            $table->unsignedFloat('max_initial_fee')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mortgages');
    }
};
