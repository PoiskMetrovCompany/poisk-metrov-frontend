<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class BuiltYearFilterCommand extends AbstractFilterCommand
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
            $conditions = [];
            
            foreach ($value as $val) {
                $condition = $this->buildCondition($val);
                if ($condition !== null) {
                    $conditions[] = $condition;
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

        if (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $conditions = [];
            
            foreach ($values as $val) {
                $condition = $this->buildCondition($val);
                if ($condition !== null) {
                    $conditions[] = $condition;
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

        $condition = $this->buildCondition($value);
        if ($condition !== null) {
            return $query->where($condition[0], $condition[1], $condition[2]);
        }

        return $query;
    }

    /**
     * @param mixed $value
     * @return array|null
     */
    private function buildCondition($value): ?array
    {
        $normalizedValue = $this->normalizeStringValue($value);
        
        if ($normalizedValue === null) {
            return null;
        }

        $currentYear = (int)date('Y');
        
        switch ($normalizedValue) {
            case 'Сдан':
                return ['built_year', '<=', $currentYear];
            case 'Позднее':
                return ['built_year', '>', $currentYear];
            default:
                if (is_numeric($normalizedValue)) {
                    $year = (int)$normalizedValue;
                    if ($year >= 1900 && $year <= 2100) {
                        return ['built_year', '=', $year];
                    }
                }
                break;
        }

        return null;
    }
}
