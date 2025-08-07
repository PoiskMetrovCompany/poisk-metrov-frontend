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
        if (Schema::hasTable('residential_complexes')) {
            return;
        }

        Schema::create('residential_complexes', function (Blueprint $table) {
            $table->id();
            //Код латиницей
            $table->string('code', 255)->unique();
            //Старый код здания
            $table->string('old_code', 255)->nullable();
            //Название ЖК
            $table->string('name', 255);
            //Название застройщика
            $table->string('builder', 255);
            //Описание ЖК
            $table->longText('description');
            //Широта первого попавшегося комплекса
            $table->float('latitude', 16, 10);
            //Долгота первого попавшегося комплекса
            $table->float('longitude', 16, 10);

            //id локации
            $table->foreignId('location_id')->constrained('locations');

            //Адрес
            $table->string('address', 255);
            //Название станции метро
            $table->string('metro_station', 255)->nullable();
            //Сколько минут добираться до метро...
            $table->unsignedSmallInteger('metro_time')->nullable();
            //... пешком или на транспорте
            $table->string('metro_type', 255)->nullable();
            $table->longText('infrastructure')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residential_complexes');
    }
};
