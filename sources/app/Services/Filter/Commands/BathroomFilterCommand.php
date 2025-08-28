<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class BathroomFilterCommand extends AbstractFilterCommand
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

        // Применимо только к таблице квартир
        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';
        if (!$isApartmentQuery) {
            return $query;
        }

        $bathroom = $this->normalizeStringValue($value);
        if ($bathroom !== null) {
            return $query->where('bathroom_unit', '=', $bathroom);
        }

        return $query;
    }
}


