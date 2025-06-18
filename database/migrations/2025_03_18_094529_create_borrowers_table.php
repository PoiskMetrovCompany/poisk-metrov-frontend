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
        Schema::create('borrowers', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('booked_order_id')->references('id')->on('booked_orders')->onDelete('cascade');
            $table->string('fio');
            $table->date('birth_date');
            $table->string('citizenship');
            $table->string('education');
            $table->string('marital_status');
            $table->bigInteger('income');
            $table->boolean('is_primary');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowers');
    }
};
