<?php

namespace App\Core\Services;

use Illuminate\Support\Str;

interface BackupHistoryServiceInterface
{
    /**
     * @param array $attributes
     * @return void
     */
    public function setHistory(array $attributes): void;

    /**
     * @return array
     */
    public function getHistoryAll(): array;

    /**
     * @param array $attributes
     * @return void
     */
    public function handler(array $attributes): void;
}
