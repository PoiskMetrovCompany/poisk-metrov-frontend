<?php

use App\Core\Common\MaritalStatusesEnum;
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
        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('key');
            $table->enum('title', [
                MaritalStatusesEnum::REGISERED_MARRIAGE->value,
                MaritalStatusesEnum::NOT_REGISERED_MARRIAGE->value,
                MaritalStatusesEnum::NOT_MARRIAGE->value,
            ])->default(MaritalStatusesEnum::NOT_MARRIAGE->value);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marital_statuses');
    }
};
