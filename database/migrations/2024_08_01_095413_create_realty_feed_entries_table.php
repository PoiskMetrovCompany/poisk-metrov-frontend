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
        Schema::create('realty_feed_entries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('url', 2048);
            $table->string('format', 32);
            $table->string('city', 32);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realty_feed_entries');
    }
};
