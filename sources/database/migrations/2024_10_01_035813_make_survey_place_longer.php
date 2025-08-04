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
        Schema::table('current_surveys', function (Blueprint $table) {
            $table->string('place', 64)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_surveys', function (Blueprint $table) {
            $table->string('place', 10)->nullable()->default(null)->change();
        });
    }
};
