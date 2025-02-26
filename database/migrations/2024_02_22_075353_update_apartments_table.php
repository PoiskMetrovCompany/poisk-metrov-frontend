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
            $table->string('renovation_url', 255);
            $table->string('mortgage', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apartments', function (Blueprint $table) {
            $table->dropColumn('renovation_url');
            $table->dropColumn('mortgage');
        });
    }
};
