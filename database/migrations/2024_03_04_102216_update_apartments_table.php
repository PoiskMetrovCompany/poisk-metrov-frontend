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
            $table->string('renovation_url', 255)->nullable()->change();
            $table->string('floor_plan_url', 255)->nullable()->change();
            $table->string('windows_directions', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('apartments')) {
            return;
        }

        Schema::table('apartments', function (Blueprint $table) {
            $table->string('renovation_url', 255)->nullable(false)->change();
            $table->string('floor_plan_url', 255)->nullable(false)->change();
            $table->string('windows_directions', 255)->nullable(false)->change();
        });
    }
};
