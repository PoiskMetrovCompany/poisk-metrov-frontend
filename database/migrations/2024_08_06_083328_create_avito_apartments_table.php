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
        Schema::create('avito_apartments', function (Blueprint $table) {
            $table->id();
            //ID квартиры из фида
            $table->string('offer_id', 64);
            //ID комплекса
            $table->foreignId('building_id')->constrained('avito_buildings');
            //Тип - Студия, Квартира, Апартамент, Гараж, Кладовка
            $table->string('apartment_type', 255);
            //Номер квартиры в здании (не число)
            $table->string('apartment_number', 32)->nullable();
            //Отделка - Подготовка под чистовую отделку, Отделка "под ключ"
            $table->string('renovation', 255)->nullable();
            //Тип балкона - лоджия, балкон, нет
            $table->string('balcony', 255)->nullable();
            //Этаж квартиры
            $table->unsignedTinyInteger('floor');
            //Ссылка на картинку плана квартиры
            $table->string('plan_url', 255)->nullable();
            //Количество комнат
            $table->tinyInteger('room_count')->nullable();
            //Цена квартиры
            $table->unsignedBigInteger('price')->nullable();
            //Площадь
            $table->unsignedFloat('area')->nullable();
            //Кухня
            $table->unsignedFloat('kitchen_space')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avito_apartments');
    }
};
