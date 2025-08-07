<?php

namespace App\Core\Interfaces\Services;

interface BackupHistoryServiceInterface
{
    /**
     * @param array $attributes
     * @return void
     */
    public function handler(array $attributes): void;
}
