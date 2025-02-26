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
        Schema::create('complex_buildings', function (Blueprint $table) {
            $table->id();
            //ID комплекса
            $table->foreignId('complex_id')->constrained('complex_residential_complexes');
            $table->unsignedInteger('native_id');
            $table->string('address', 255)->nullable()->default(null);
            //Количество этажей в корпусе
            $table->unsignedTinyInteger('floors_total')->nullable()->default(null);
            //В одном ЖК может быть несколько разных зданий с разными свойствами
            //Тип материалов здания
            $table->string('building_materials', 64)->nullable()->default(null);
            //Состояние постройки - unfinished, hand-over
            $table->string('building_state', 64)->nullable()->default(null);
            //Корпус
            $table->string('building_section', 64)->default('Корпус 1')->nullable();
            //Широта
            $table->float('latitude', 16, 10)->nullable()->default(null);
            //Долгота
            $table->float('longitude', 16, 10)->nullable()->default(null);
            //Квартал сдачи
            $table->unsignedTinyInteger('ready_quarter')->nullable()->default(null);
            //Год сдачи
            $table->unsignedSmallInteger('built_year')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complex_buildings');
    }
};
