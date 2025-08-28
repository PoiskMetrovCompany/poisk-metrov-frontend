<?php

namespace App\Core\Abstracts;

use App\Core\Interfaces\Services\FilterCommandInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Абстрактный базовый класс для команд фильтрации
 */
abstract class AbstractFilterCommand implements FilterCommandInterface
{
    /**
     * Проверить, что значение не пустое
     *
     * @param mixed $value
     * @return bool
     */
    public function canExecute($value): bool
    {
        return !is_null($value) && $value !== '' && $value !== [];
    }

    /**
     * Нормализовать числовое значение
     *
     * @param mixed $value
     * @return float|int|null
     */
    protected function normalizeNumericValue($value)
    {
        if (is_numeric($value)) {
            return is_float($value) ? (float)$value : (int)$value;
        }
        return null;
    }

    /**
     * Нормализовать строковое значение
     *
     * @param mixed $value
     * @return string|null
     */
    protected function normalizeStringValue($value): ?string
    {
        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }
        return null;
    }
}
