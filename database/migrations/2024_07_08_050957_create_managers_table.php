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
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 32);
            $table->string('document_name', 255);
            $table->string('avatar_file_name', 255)->nullable();
            $table->string('city', 32);
            //По всей видимости айдишник в разных ЦРМ может быть одинаковый (например 1052998)
            $table->unsignedBigInteger('crm_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
