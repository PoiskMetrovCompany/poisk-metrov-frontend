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

        $floor = $this->normalizeNumericValue($value);

        if ($floor !== null) {
            return $query->where('floor', '=', $floor);
        }

        return $query;
    }
}
