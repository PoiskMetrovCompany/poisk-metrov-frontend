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
        Schema::create('borrower_works', function (Blueprint $table) {
            $table->id('id');
            // Fk
            $table->foreignId('borrower_id')->constrained('borrowers')->onDelete('cascade');

            $table->string('organization_name');
            $table->string('inn');
            $table->string('phone');
            $table->string('job_title');
            $table->string('employment_contract');
            $table->string('category_position_held');
            $table->integer('number_of_employees');
            $table->integer('experience');
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
        Schema::dropIfExists('borrowr_works');
    }
};
