<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Меняем тип поля на VARCHAR временно
        DB::statement("ALTER TABLE candidate_profiles MODIFY COLUMN status VARCHAR(255) DEFAULT 'Новая анкета'");

        // Очищаем все значения, устанавливая дефолтное
        DB::statement("UPDATE candidate_profiles SET status = 'Новая анкета'");

        // Изменяем поле status на VARCHAR, чтобы хранить любые значения
        DB::statement("ALTER TABLE candidate_profiles MODIFY COLUMN status VARCHAR(255) DEFAULT 'Новая анкета'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем enum к предыдущему состоянию
        DB::statement("ALTER TABLE candidate_profiles MODIFY COLUMN status ENUM(
            '',
            'Новая анкета',
            'Проверен',
            'Отклонен',
            'Нужна доработка',
            'Принят',
            'Не принят',
            'Вышел',
            'Не вышел'
        ) DEFAULT 'Новая анкета'");
    }
};
