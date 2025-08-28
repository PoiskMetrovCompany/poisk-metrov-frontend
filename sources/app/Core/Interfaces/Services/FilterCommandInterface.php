<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Database\Eloquent\Builder;

/**
 * Интерфейс для команд фильтрации
 */
interface FilterCommandInterface
{
    /**
     * Выполнить фильтрацию
     *
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function execute(Builder $query, $value): Builder;

    /**
     * Проверить, можно ли выполнить команду
     *
     * @param mixed $value
     * @return bool
     */
    public function canExecute($value): bool;
}
