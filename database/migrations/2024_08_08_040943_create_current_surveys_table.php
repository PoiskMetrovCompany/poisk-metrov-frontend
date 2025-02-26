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
        Schema::create('current_surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_id');
            $table->integer('curr_question')->default(0);
            $table->string('date', 255)->nullable();
            $table->string('agent_fio', 255)->nullable();
            $table->string('client', 255)->nullable();
            $table->string('is_first', 255)->nullable();
            $table->string('construction', 255)->nullable();
            $table->string('builder', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('is_lead', 255)->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('builder_percent', 10)->nullable();
            $table->bigInteger('commission')->nullable();
            $table->string('place', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_surveys');
    }
};
