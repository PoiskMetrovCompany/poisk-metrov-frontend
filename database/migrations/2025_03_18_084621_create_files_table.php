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
        Schema::create('files', function (Blueprint $table) {
            $table->id('id');
            // Fk
            $table->foreignId('user_id')->constrained('users');

            $table->uuid('key');
            $table->string('name');
            $table->string('input_name');
            $table->string('mime');
            $table->string('size');
            $table->string('extension');
            $table->string('category');
            $table->string('comment')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            // Indexes
            $table->index('user_id');
            $table->unique('key');
            $table->index('extension');
            $table->index('category');
            $table->index('comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
