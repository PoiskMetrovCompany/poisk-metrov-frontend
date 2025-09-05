<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use App\Core\Common\RootEntityEnum;
use Illuminate\Database\Eloquent\Builder;


class ParkingFilterCommand extends AbstractFilterCommand
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

        $parking = $this->normalizeStringValue($value);

        if ($parking !== null) {
            $isApartmentQuery = $query->getModel()->getTable() === 'apartments';

            if ($isApartmentQuery) {
                return $query->whereHas('residentialComplex', function ($q) use ($parking) {
                    $q->where('parking', '=', $parking);
                });
            } else {
                return $query->where('parking', '=', $parking);
            }
        }

        return $query;
    }
}
