<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

/**
 * Команда для фильтрации по высоте потолков
 */
class CeilingHeightFilterCommand extends AbstractFilterCommand
{
    /**
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
                return $query->whereBetween('ceiling_height', [$min, $max]);
            } elseif ($min !== null) {
                return $query->where('ceiling_height', '>=', $min);
            } elseif ($max !== null) {
                return $query->where('ceiling_height', '<=', $max);
            }
        } elseif (is_numeric($value)) {
            $height = $this->normalizeNumericValue($value);
            if ($height !== null) {
                return $query->where('ceiling_height', '=', $height);
            }
        }

        return $query;
    }
}
