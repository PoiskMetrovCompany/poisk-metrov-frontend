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
            $table->string('current_step', 255)->default('approximate_name')->nullable();
            $table->dropColumn(['curr_question']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_surveys', function (Blueprint $table) {
            $table->integer('curr_question')->default(0);
            $table->dropColumn(['current_step']);
        });
    }
};
