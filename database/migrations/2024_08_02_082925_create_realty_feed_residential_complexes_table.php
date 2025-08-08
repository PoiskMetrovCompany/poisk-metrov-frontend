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
        Schema::create('realty_feed_residential_complexes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 255);
            $table->string('address', 255);
            $table->longText('description');
            //Название станции метро
            $table->string('metro_station', 255)->nullable();
            //Сколько минут добираться до метро...
            $table->unsignedSmallInteger('metro_time')->nullable();
            //... пешком или на транспорте
            $table->string('metro_type', 255)->nullable();
            $table->string('builder', 255)->nullable();
            $table->foreignId('location_id')->constrained('realty_feed_locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realty_feed_residential_complexes');
    }
};
