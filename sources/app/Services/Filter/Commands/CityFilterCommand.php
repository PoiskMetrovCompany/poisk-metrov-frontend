<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class CityFilterCommand extends AbstractFilterCommand
{
    /**
     * Фильтр по городу. Ожидает код города в таблице locations.code
     * Для квартир — через связь residentialComplex.location
     * Для ЖК — через связь location
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function execute(Builder $query, $value): Builder
    {
        if (!$this->canExecute($value)) {
            return $query;
        }

        $code = $this->normalizeStringValue($value);
        if ($code === null) {
            return $query;
        }

        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';
        if ($isApartmentQuery) {
            return $query->whereHas('residentialComplex.location', function ($lq) use ($code) {
                $lq->where('code', '=', $code);
            });
        }

        // ЖК
        return $query->whereHas('location', function ($lq) use ($code) {
            $lq->where('code', '=', $code);
        });
    }
}


