<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class FloorCountsFilterCommand extends AbstractFilterCommand
{
    /**
     * Фильтрация по количеству этажей в ЖК (residential_complexes.floors)
     * Если запрос идёт по квартирам — применяем через связь residentialComplex
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

        $floors = $this->normalizeNumericValue($value);
        if ($floors === null) {
            return $query;
        }

        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';
        if ($isApartmentQuery) {
            return $query->whereHas('residentialComplex', function ($q) use ($floors) {
                $q->where('floors', '=', $floors);
            });
        }

        return $query->where('floors', '=', $floors);
    }
}


