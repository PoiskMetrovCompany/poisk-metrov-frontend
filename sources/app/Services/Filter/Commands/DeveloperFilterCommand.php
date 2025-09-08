<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class DeveloperFilterCommand extends AbstractFilterCommand
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

        $developer = $this->normalizeStringValue($value);
        if ($developer === null) {
            return $query;
        }

        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';
        if ($isApartmentQuery) {
            return $query->whereHas('residentialComplex', function ($q) use ($developer) {
                $q->where('builder', '=', $developer);
            });
        }

        return $query->where('builder', '=', $developer);
    }
}


