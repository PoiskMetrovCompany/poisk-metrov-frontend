<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

/**
 * Команда для фильтрации по общей площади
 */
class AreaFilterCommand extends AbstractFilterCommand
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
                return $query->whereBetween('area', [$min, $max]);
            } elseif ($min !== null) {
                return $query->where('area', '>=', $min);
            } elseif ($max !== null) {
                return $query->where('area', '<=', $max);
            }
        } elseif (is_numeric($value)) {
            $area = $this->normalizeNumericValue($value);
            if ($area !== null) {
                return $query->where('area', '=', $area);
            }
        }

        return $query;
    }
}
