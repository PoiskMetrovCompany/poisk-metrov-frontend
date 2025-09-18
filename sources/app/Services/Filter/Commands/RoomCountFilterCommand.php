<?php

namespace App\Services\Filter\Commands;

use App\Core\Abstracts\AbstractFilterCommand;
use Illuminate\Database\Eloquent\Builder;

/**
 * Команда для фильтрации по количеству комнат
 */
class RoomCountFilterCommand extends AbstractFilterCommand
{
    /**
     * Выполнить фильтрацию по количеству комнат
     * Поддерживает:
     * - Одно значение: 2
     * - Массив значений: [1,2,3]
     * - Диапазон: "5+" (от 5 и выше)
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

        if (is_array($value)) {
            $roomCounts = array_filter(array_map([$this, 'normalizeNumericValue'], $value));
            if (!empty($roomCounts)) {
                return $query->whereIn('room_count', $roomCounts);
            }
            return $query;
        }

        if (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $conditions = [];
            
            foreach ($values as $val) {
                if (str_ends_with($val, '+')) {
                    $minRooms = $this->normalizeNumericValue(rtrim($val, '+'));
                    if ($minRooms !== null) {
                        $conditions[] = ['room_count', '>=', $minRooms];
                    }
                } else {
                    $roomCount = $this->normalizeNumericValue($val);
                    if ($roomCount !== null) {
                        $conditions[] = ['room_count', '=', $roomCount];
                    }
                }
            }
            
            if (!empty($conditions)) {
                return $query->where(function($q) use ($conditions) {
                    foreach ($conditions as $condition) {
                        $q->orWhere($condition[0], $condition[1], $condition[2]);
                    }
                });
            }
            return $query;
        }

        if (is_string($value) && str_ends_with($value, '+')) {
            $minRooms = $this->normalizeNumericValue(rtrim($value, '+'));
            if ($minRooms !== null) {
                return $query->where('room_count', '>=', $minRooms);
            }
        }

        $roomCount = $this->normalizeNumericValue($value);
        if ($roomCount !== null) {
            return $query->where('room_count', '=', $roomCount);
        }

        return $query;
    }
}
