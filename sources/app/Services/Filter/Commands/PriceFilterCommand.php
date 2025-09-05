<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

/**
 * Команда для фильтрации по цене
 */
class PriceFilterCommand extends AbstractFilterCommand
{
    /**
     * Выполнить фильтрацию по цене
     *
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function execute(Builder $query, $value): Builder
    {
        if (!$this->canExecute($value)) {
            return $query;
        }

        if (is_string($value) && strpos($value, '-') !== false) {
            [$min, $max] = explode('-', $value, 2);

            $min = $this->normalizeNumericValue($min);
            $max = $this->normalizeNumericValue($max);

            if ($min !== null && $max !== null) {
                return $query->whereBetween('price', [$min, $max]);
            } elseif ($min !== null) {
                return $query->where('price', '>=', $min);
            } elseif ($max !== null) {
                return $query->where('price', '<=', $max);
            }
        } elseif (is_numeric($value)) {
            $price = $this->normalizeNumericValue($value);
            if ($price !== null) {
                return $query->where('price', '=', $price);
            }
        }

        return $query;
    }
}
