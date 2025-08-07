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
        Schema::create('version2_apartments', function (Blueprint $table) {
            $table->id();
            //ID квартиры из фида
            $table->string('offer_id', 64);
            //ID комплекса
            $table->foreignId('building_id')->constrained('version2_buildings');
            //Цена квартиры
            $table->unsignedBigInteger('price');
            //Этаж квартиры
            $table->unsignedTinyInteger('floor');
            //Площадь
            $table->unsignedFloat('area');
            //Номер квартиры в здании (не число)
            $table->string('apartment_number', 32)->nullable()->default(null);
            //Отделка - Подготовка под чистовую отделку, Отделка "под ключ"
            $table->string('renovation', 255)->nullable()->default(null);
            //Ссылка на картинку плана квартиры
            $table->string('plan_url', 255)->nullable()->default(null);
            //Количество комнат
            $table->tinyInteger('room_count')->nullable()->default(null);
            //Кухня
            $table->unsignedFloat('living_space')->nullable()->default(null);
            //Кухня
            $table->unsignedFloat('kitchen_space')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version2_apartments');
    }
};
