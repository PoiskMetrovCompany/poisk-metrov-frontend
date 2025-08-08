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
        Schema::create('realty_feed_buildings', function (Blueprint $table) {
            $table->id();
            //ID комплекса
            $table->foreignId('complex_id')->constrained('realty_feed_residential_complexes');
            //Количество этажей в корпусе
            $table->unsignedTinyInteger('floors_total')->nullable();
            //В одном ЖК может быть несколько разных зданий с разными свойствами
            //Тип материалов здания
            $table->string('building_materials', 64)->nullable();
            //Состояние постройки - unfinished, hand-over
            $table->string('building_state', 64)->nullable();
            //Очередь сдачи здания
            $table->string('building_phase', 64)->nullable();
            //Корпус
            $table->string('building_section', 64)->nullable();
            //Широта
            $table->float('latitude', 16, 10)->nullable();
            //Долгота
            $table->float('longitude', 16, 10)->nullable();
            //Квартал сдачи
            $table->unsignedTinyInteger('ready_quarter')->nullable();
            //Год сдачи
            $table->unsignedSmallInteger('built_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realty_feed_buildings');
    }
};
