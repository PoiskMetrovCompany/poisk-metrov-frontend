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
        Schema::table('renovation_urls', function (Blueprint $table) {
            $table->foreign('offer_id')->references('offer_id')->on('apartments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renovation_urls', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
        });
    }
};
