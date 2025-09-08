<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class SearchFilterCommand extends AbstractFilterCommand
{
    /**
     * Нестрогий поиск по нескольким полям:
     * - ЖК: name, builder, address, metro_station, а также через связь location: district, locality, region
     * - Квартиры: через связь residentialComplex c теми же полями
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function execute(Builder $query, $value): Builder
    {
        if (!$this->canExecute($value)) {
            return $query;
        }

        $term = $this->normalizeStringValue($value);
        if ($term === null) {
            return $query;
        }

        // Очистим многоточия/точки и разобьем по запятым на токены
        $clean = trim(preg_replace('/\.+/u', ' ', $term));
        $tokens = array_values(array_filter(array_map('trim', preg_split('/\s*,\s*/u', $clean))));
        if (empty($tokens)) {
            return $query;
        }

        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';
        if ($isApartmentQuery) {
            return $query->whereHas('residentialComplex', function ($q) use ($tokens) {
                foreach ($tokens as $token) {
                    $like = '%' . $token . '%';
                    $q->where(function ($qq) use ($like) {
                        $qq->where('name', 'LIKE', $like)
                           ->orWhere('builder', 'LIKE', $like)
                           ->orWhere('address', 'LIKE', $like)
                           ->orWhere('metro_station', 'LIKE', $like)
                           ->orWhereHas('location', function ($lq) use ($like) {
                               $lq->where('district', 'LIKE', $like)
                                  ->orWhere('locality', 'LIKE', $like)
                                  ->orWhere('region', 'LIKE', $like);
                           });
                    });
                }
            });
        }

        // Поиск по самим ЖК (AND по токенам, OR по полям)
        foreach ($tokens as $token) {
            $like = '%' . $token . '%';
            $query = $query->where(function ($qq) use ($like) {
                $qq->where('name', 'LIKE', $like)
                   ->orWhere('builder', 'LIKE', $like)
                   ->orWhere('address', 'LIKE', $like)
                   ->orWhere('metro_station', 'LIKE', $like)
                   ->orWhereHas('location', function ($lq) use ($like) {
                       $lq->where('district', 'LIKE', $like)
                          ->orWhere('locality', 'LIKE', $like)
                          ->orWhere('region', 'LIKE', $like);
                   });
            });
        }

        return $query;
    }
}


