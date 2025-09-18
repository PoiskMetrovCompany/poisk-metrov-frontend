<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;


class LivingAreaFilterCommand extends AbstractFilterCommand
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

        if (is_array($value)) {
            $areas = array_filter(array_map([$this, 'normalizeNumericValue'], $value));
            if (!empty($areas)) {
                return $query->whereIn('living_space', $areas);
            }
            return $query;
        }

        if (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $conditions = [];
            
            foreach ($values as $val) {
                if (str_ends_with($val, '+')) {
                    $minArea = $this->normalizeNumericValue(rtrim($val, '+'));
                    if ($minArea !== null) {
                        $conditions[] = ['living_space', '>=', $minArea];
                    }
                } elseif (str_contains($val, '-')) {
                    [$min, $max] = explode('-', $val, 2);
                    $minArea = $this->normalizeNumericValue($min);
                    $maxArea = $this->normalizeNumericValue($max);
                    if ($minArea !== null && $maxArea !== null) {
                        $conditions[] = ['living_space', 'BETWEEN', [$minArea, $maxArea]];
                    } elseif ($minArea !== null) {
                        $conditions[] = ['living_space', '>=', $minArea];
                    } elseif ($maxArea !== null) {
                        $conditions[] = ['living_space', '<=', $maxArea];
                    }
                } else {
                    $area = $this->normalizeNumericValue($val);
                    if ($area !== null) {
                        $conditions[] = ['living_space', '=', $area];
                    }
                }
            }
            
            if (!empty($conditions)) {
                return $query->where(function($q) use ($conditions) {
                    foreach ($conditions as $condition) {
                        if ($condition[1] === 'BETWEEN') {
                            $q->orWhereBetween($condition[0], $condition[2]);
                        } else {
                            $q->orWhere($condition[0], $condition[1], $condition[2]);
                        }
                    }
                });
            }
            return $query;
        }

        if (is_string($value) && str_ends_with($value, '+')) {
            $minArea = $this->normalizeNumericValue(rtrim($value, '+'));
            if ($minArea !== null) {
                return $query->where('living_space', '>=', $minArea);
            }
        }

        if (is_string($value) && strpos($value, '-') !== false) {
            [$min, $max] = explode('-', $value, 2);

            $min = $this->normalizeNumericValue($min);
            $max = $this->normalizeNumericValue($max);

            if ($min !== null && $max !== null) {
                return $query->whereBetween('living_space', [$min, $max]);
            } elseif ($min !== null) {
                return $query->where('living_space', '>=', $min);
            } elseif ($max !== null) {
                return $query->where('living_space', '<=', $max);
            }
        } elseif (is_numeric($value)) {
            $area = $this->normalizeNumericValue($value);
            if ($area !== null) {
                return $query->where('living_space', '=', $area);
            }
        }

        return $query;
    }
}
