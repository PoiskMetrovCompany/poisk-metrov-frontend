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
        Schema::create('nmarket_apartments', function (Blueprint $table) {
            $table->id();
            //ID квартиры из фида
            $table->string('offer_id', 64)->unique();
            //ID комплекса
            $table->string('complex_code', 255);
            //Тип - Студия, Квартира, Апартамент
            $table->string('apartment_type', 255);
            //Отделка - Подготовка под чистовую отделку, Отделка "под ключ"
            $table->string('renovation', 255);
            //Тип балкона - лоджия, балкон, нет
            $table->string('balcony', 255);
            //Тип санузла - 2, совмещенный, раздельный
            $table->string('bathroom_unit', 255);

            //Этаж квартиры
            $table->unsignedTinyInteger('floor');
            //Количество этажей в ЖК
            $table->unsignedTinyInteger('floors_total');
            //Номер апартаментов в здании (не число)
            $table->string('apartment_number', 32);

            //В одном ЖК может быть несколько разных зданий с разными свойствами
            //Тип материалов здания
            $table->string('building_materials', 64);
            //Состояние постройки - unfinished, hand-over
            $table->string('building_state', 64);
            //Очередь сдачи здания
            $table->string('building_phase', 64);
            //Корпус
            $table->string('building_section', 64);
            //Широта
            $table->float('latitude', 16, 10);
            //Долгота
            $table->float('longitude', 16, 10);

            //Квартал сдачи
            $table->unsignedTinyInteger('ready_quarter');
            //Год сдачи
            $table->unsignedSmallInteger('built_year');

            //Ссылка на картинку плана квартиры
            $table->string('plan_URL', 255)->nullable();
            //Ссылка на картинку плана этажа
            $table->string('floor_plan_url', 255)->nullable();
            //Высота потолка
            $table->unsignedFloat('ceiling_height');
            //Количество комнат
            $table->tinyInteger('room_count');
            //Цена квартиры
            $table->unsignedBigInteger('price');
            //Площадь
            $table->unsignedFloat('area');
            //Жилая площадь
            $table->unsignedFloat('living_space');
            //Кухня
            $table->unsignedFloat('kitchen_space');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nmarket_apartments');
    }
};
