<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;


class ElevatorFilterCommand extends AbstractFilterCommand
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

        $elevator = $this->normalizeStringValue($value);

        if ($elevator !== null) {
            $isApartmentQuery = $query->getModel()->getTable() === 'apartments';

            if ($isApartmentQuery) {
                return $query->whereHas('residentialComplex', function ($q) use ($elevator) {
                    $q->where('elevator', '=', $elevator);
                });
            } else {
                return $query->where('elevator', '=', $elevator);
            }
        }

        return $query;
    }
}
