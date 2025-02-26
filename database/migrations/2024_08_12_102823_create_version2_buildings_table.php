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
        Schema::create('version2_buildings', function (Blueprint $table) {
            $table->id();
            //ID комплекса
            $table->foreignId('complex_id')->constrained('version2_residential_complexes');
            $table->string('address', 255)->nullable()->default(null);
            //Количество этажей в корпусе
            $table->unsignedTinyInteger('floors_total')->nullable()->default(null);
            //Корпус
            $table->string('building_section', 64)->default('Корпус 1')->nullable();
            //Широта
            $table->float('latitude', 16, 10)->nullable()->default(null);
            //Долгота
            $table->float('longitude', 16, 10)->nullable()->default(null);
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
        Schema::dropIfExists('version2_buildings');
    }
};
