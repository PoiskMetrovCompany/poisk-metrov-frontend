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
        if (Schema::hasTable('docs')) {
            return;
        }
        
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complex_id')->constrained('residential_complexes');
            $table->string('doc_url', 255);
            $table->string('doc_name', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docs');
    }
};
