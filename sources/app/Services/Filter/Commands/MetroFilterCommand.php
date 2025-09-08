<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class MetroFilterCommand extends AbstractFilterCommand
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

        $metroTime = $this->normalizeNumericValue($value);

        if ($metroTime !== null) {
            $isApartmentQuery = $query->getModel()->getTable() === 'apartments';

            if ($isApartmentQuery) {
                return $query->whereHas('residentialComplex', function ($q) use ($metroTime) {
                    $q->where('metro_time', '<=', $metroTime);
                });
            } else {
                return $query->where('metro_time', '<=', $metroTime);
            }
        }

        return $query;
    }
}
