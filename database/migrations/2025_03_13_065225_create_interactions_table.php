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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('key');

            // FK
            $table->foreignId('apartment_id')->constrained('apartments', 'id');
            $table->foreignId('manager_id')->constrained('managers', 'id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignUuid('reservation_key')->constrained('reservations', 'key')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            // Indexes
            $table->unique('key');
            $table->index('manager_id');
            $table->index('user_id');
            $table->index('apartment_id');
            $table->index('reservation_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};
