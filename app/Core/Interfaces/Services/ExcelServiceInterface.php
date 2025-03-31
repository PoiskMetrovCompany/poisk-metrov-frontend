<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Support\Collection;

interface ExcelServiceInterface
{
    /**
     * @param string $cityCode
     * @return Collection
     */
    public function getManagerPhonePairs(string $cityCode): Collection;
}
