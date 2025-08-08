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
        Schema::create('borrower_passports', function (Blueprint $table) {
            $table->id('id');
            // Fk
            $table->foreignId('borrower_id')->constrained('borrowers')->onDelete('cascade');

            $table->integer('number');
            $table->date('issue_date');
            $table->integer('code');
            $table->string('issued_by');
            $table->string('place_of_birth');
            $table->string('registration_address_ru');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            // Indexes
            $table->index('issue_date');
            $table->unique('number');
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrower_passports');
    }
};
