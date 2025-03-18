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
        Schema::create('booked_orders', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('key');
            // Fk
            $table->foreignUuid('reservation_id')->references('id')->on('reservations')->onDelete('cascade');

            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            // Indexes
            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booked_orders');
    }
};
