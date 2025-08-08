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
        if (!Schema::hasTable('apartments')) {
            return;
        }

        Schema::table('apartments', function (Blueprint $table) {
            $table->string('floor_plan_url', 255);
            $table->string('windows_directions', 255);
            $table->boolean('is_new')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('floor_plan_url');
            $table->dropColumn('windows_directions');
            $table->dropColumn('is_new');
        });
    }
};
