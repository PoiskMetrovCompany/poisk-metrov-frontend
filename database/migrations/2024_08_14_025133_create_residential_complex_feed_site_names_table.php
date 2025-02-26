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
        Schema::create('residential_complex_feed_site_names', function (Blueprint $table) {
            $table->id();
            $table->string('feed_name', 255);
            $table->string('site_name', 255)->nullable();
            $table->boolean('create_new')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residential_complex_feed_site_names');
    }
};
