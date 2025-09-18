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

        if (is_array($value)) {
            $renovations = array_filter(array_map([$this, 'normalizeStringValue'], $value));
            if (!empty($renovations)) {
                return $query->whereIn('renovation', $renovations);
            }
            return $query;
        }

        if (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $renovations = array_filter(array_map([$this, 'normalizeStringValue'], $values));
            if (!empty($renovations)) {
                return $query->whereIn('renovation', $renovations);
            }
            return $query;
        }

        $renovation = $this->normalizeStringValue($value);

        if ($renovation !== null) {
            return $query->where('renovation', '=', $renovation);
        }

        return $query;
    }
}
