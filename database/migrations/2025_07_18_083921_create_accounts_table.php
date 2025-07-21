<?php

use App\Core\Common\RoleEnum;
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
        // TODO: это временное решение после рефакторинга базы данных переделать
        Schema::create('accounts', function (Blueprint $table) {
            $table->id('id');
            $table->string('key');
            $table->enum('role', [
                RoleEnum::SecurityGuard->value,
                RoleEnum::Candidate->value,
            ]);
            $table->string('phone')->unique();
            $table->string('secret')->nullable();
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
        Schema::dropIfExists('accounts');
    }
};
