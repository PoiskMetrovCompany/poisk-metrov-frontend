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
        Schema::create('borrower_documents', function (Blueprint $table) {
            $table->id('id');
            // Fk
            $table->foreignId('borrower_id')->constrained('borrowers')->onDelete('cascade');

            $table->uuid('file_key');
            $table->enum('documents_type', [
                'Паспорт',
            ]);
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
        Schema::dropIfExists('borrower_documents');
    }
};
