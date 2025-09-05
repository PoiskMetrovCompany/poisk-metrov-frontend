<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;


class RenovationFilterCommand extends AbstractFilterCommand
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

        $renovation = $this->normalizeStringValue($value);

        if ($renovation !== null) {
            return $query->where('renovation', '=', $renovation);
        }

        return $query;
    }
}
