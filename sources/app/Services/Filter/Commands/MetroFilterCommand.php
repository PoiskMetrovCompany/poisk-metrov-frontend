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

        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';

        if (is_array($value)) {
            $metroTimes = array_filter(array_map([$this, 'normalizeNumericValue'], $value));
            if (!empty($metroTimes)) {
                $maxTime = max($metroTimes);
                if ($isApartmentQuery) {
                    return $query->whereHas('residentialComplex', function ($q) use ($maxTime) {
                        $q->where('metro_time', '<=', $maxTime);
                    });
                } else {
                    return $query->where('metro_time', '<=', $maxTime);
                }
            }
            return $query;
        }

        if (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $metroTimes = array_filter(array_map([$this, 'normalizeNumericValue'], $values));
            if (!empty($metroTimes)) {
                $maxTime = max($metroTimes);
                if ($isApartmentQuery) {
                    return $query->whereHas('residentialComplex', function ($q) use ($maxTime) {
                        $q->where('metro_time', '<=', $maxTime);
                    });
                } else {
                    return $query->where('metro_time', '<=', $maxTime);
                }
            }
            return $query;
        }

        if (is_string($value) && str_ends_with($value, '+')) {
            $minTime = $this->normalizeNumericValue(rtrim($value, '+'));
            if ($minTime !== null) {
                if ($isApartmentQuery) {
                    return $query->whereHas('residentialComplex', function ($q) use ($minTime) {
                        $q->where('metro_time', '>=', $minTime);
                    });
                } else {
                    return $query->where('metro_time', '>=', $minTime);
                }
            }
        }

        if (is_string($value) && strpos($value, '-') !== false) {
            [$min, $max] = explode('-', $value, 2);
            $minTime = $this->normalizeNumericValue($min);
            $maxTime = $this->normalizeNumericValue($max);

            if ($minTime !== null && $maxTime !== null) {
                if ($isApartmentQuery) {
                    return $query->whereHas('residentialComplex', function ($q) use ($minTime, $maxTime) {
                        $q->whereBetween('metro_time', [$minTime, $maxTime]);
                    });
                } else {
                    return $query->whereBetween('metro_time', [$minTime, $maxTime]);
                }
            } elseif ($minTime !== null) {
                if ($isApartmentQuery) {
                    return $query->whereHas('residentialComplex', function ($q) use ($minTime) {
                        $q->where('metro_time', '>=', $minTime);
                    });
                } else {
                    return $query->where('metro_time', '>=', $minTime);
                }
            } elseif ($maxTime !== null) {
                if ($isApartmentQuery) {
                    return $query->whereHas('residentialComplex', function ($q) use ($maxTime) {
                        $q->where('metro_time', '<=', $maxTime);
                    });
                } else {
                    return $query->where('metro_time', '<=', $maxTime);
                }
            }
        }

        $metroTime = $this->normalizeNumericValue($value);
        if ($metroTime !== null) {
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
