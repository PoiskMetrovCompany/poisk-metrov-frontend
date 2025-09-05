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
        Schema::create('user_filters', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key')->unique();
            $table->uuid('user_key');

            $table->string('type', 255)->nullable();
            $table->integer('rooms')->nullable();
            $table->string('price', 255)->nullable();
            $table->string('floors', 255)->nullable();
            $table->string('area_full', 255)->nullable();
            $table->string('area_living', 255)->nullable();
            $table->string('area_plot', 255)->nullable();
            $table->string('ceiling_height', 255)->nullable();
            $table->string('house_type', 255)->nullable();
            $table->string('finishing', 255)->nullable();
            $table->string('bathroom', 255)->nullable();
            $table->string('features', 255)->nullable();
            $table->string('security', 255)->nullable();
            $table->string('water_supply', 255)->nullable();
            $table->string('electricity', 255)->nullable();
            $table->string('sewerage', 255)->nullable();
            $table->string('heating', 255)->nullable();
            $table->string('gasification', 255)->nullable();
            $table->string('to_metro', 255)->nullable();
            $table->string('to_center', 255)->nullable();
            $table->string('to_busstop', 255)->nullable();
            $table->string('to_train', 255)->nullable();
            $table->string('near', 255)->nullable();
            $table->boolean('garden_community')->default(false);
            $table->boolean('in_city')->default(false);
            $table->string('payment_method', 255)->nullable();
            $table->string('mortgage', 255)->nullable();
            $table->string('installment_plan', 255)->nullable();
            $table->string('down_payment', 255)->nullable();
            $table->string('mortgage_programs', 255)->nullable();

            // Indexes
            $table->unique('key');
            $table->index('user_key');

            // Foreign key
            $table->foreign('user_key')->references('key')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_filters');
    }
};
