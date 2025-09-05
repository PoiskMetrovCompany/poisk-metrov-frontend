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

        $roomCount = $this->normalizeNumericValue($value);

        if ($roomCount !== null) {
            return $query->where('room_count', '=', $roomCount);
        }

        return $query;
    }
}
