<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

class MontageTypeFilterCommand extends AbstractFilterCommand
{
    /**
     * Фильтрация по типу монтажа/материалов
     * Для квартир поля нет — фильтруем по ЖК: residential_complexes.primary_material
     *
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function execute(Builder $query, $value): Builder
    {
        if (!$this->canExecute($value)) {
            return $query;
        }

        $type = $this->normalizeStringValue($value);
        if ($type === null) {
            return $query;
        }

        $isApartmentQuery = $query->getModel()->getTable() === 'apartments';
        if ($isApartmentQuery) {
            return $query->whereHas('residentialComplex', function ($q) use ($type) {
                $q->where('primary_material', '=', $type);
            });
        }

        return $query->where('primary_material', '=', $type);
    }
}


