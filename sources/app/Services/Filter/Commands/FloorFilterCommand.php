<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class FloorFilterCommand extends AbstractFilterCommand
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
            $floors = array_filter(array_map([$this, 'normalizeNumericValue'], $value));
            if (!empty($floors)) {
                return $query->whereIn('floor', $floors);
            }
            return $query;
        }

        if (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $conditions = [];
            
            foreach ($values as $val) {
                if (str_ends_with($val, '+')) {
                    $minFloor = $this->normalizeNumericValue(rtrim($val, '+'));
                    if ($minFloor !== null) {
                        $conditions[] = ['floor', '>=', $minFloor];
                    }
                } else {
                    $floor = $this->normalizeNumericValue($val);
                    if ($floor !== null) {
                        $conditions[] = ['floor', '=', $floor];
                    }
                }
            }
            
            if (!empty($conditions)) {
                return $query->where(function($q) use ($conditions) {
                    foreach ($conditions as $condition) {
                        $q->orWhere($condition[0], $condition[1], $condition[2]);
                    }
                });
            }
            return $query;
        }

        if (is_string($value) && str_ends_with($value, '+')) {
            $minFloor = $this->normalizeNumericValue(rtrim($value, '+'));
            if ($minFloor !== null) {
                return $query->where('floor', '>=', $minFloor);
            }
        }

        $floor = $this->normalizeNumericValue($value);
        if ($floor !== null) {
            return $query->where('floor', '=', $floor);
        }

        return $query;
    }
}
