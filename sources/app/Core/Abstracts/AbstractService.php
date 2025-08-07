<?php

namespace App\Core\Abstracts;

/**
 * @template TService.
 */
abstract class AbstractService
{
    public function generateRandomVerificationCode(): string
    {
        $code = "";
        $codeLength = 4;
        for ($i = 0; $i < $codeLength; $i++) {
            $code .= rand(0, 9);
        }
        return $code;
    }

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
