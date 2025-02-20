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
        Schema::create('realty_feed_locations', function (Blueprint $table) {
            $table->id();
            //Страна
            $table->string('country', 255);
            //Область
            $table->string('region', 255);
            //Код региона
            $table->string('code', 255);
            //Главный город
            //Locality name
            $table->string('capital', 255);
            //Район города или город-район, например Кольцово
            //Sublocality name
            $table->string('district', 255);
            //Название города (в области могут быть несколько городов, например в Новосибирской области есть Новосибирск, Краснообск, Кольцово и т.д.)
            $table->string('locality', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realty_feed_locations');
    }
};
