<?php

namespace App\Services;

/**
 * Class AbstractService.
 */
abstract class AbstractService
{
    public function getSimilarNames(string|null $name, string $column, string $modelName, array $additionalConditions = []): array
    {
        if ($name == null) {
            return [];
        }

        $model = app($modelName);

        return $model::where($column, 'LIKE', "%$name%")->where($additionalConditions)->pluck($column)->toArray();
    }

    public static function getFromApp(): AbstractService
    {
        return app()->get(get_called_class());
    }
}
