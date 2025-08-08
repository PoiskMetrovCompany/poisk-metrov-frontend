<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nmarket_apartments', function (Blueprint $table) {
            $table->foreign('complex_code')->references('code')->on('nmarket_residential_complexes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nmarket_apartments', function (Blueprint $table) {
            $table->dropForeign(['complex_code']);
        });
    }
};
