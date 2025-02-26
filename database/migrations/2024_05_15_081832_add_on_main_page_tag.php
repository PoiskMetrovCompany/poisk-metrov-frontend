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
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->boolean('on_main_page')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residential_complexes', function (Blueprint $table) {
            $table->dropColumn('on_main_page');
        });
    }
};
