<?php

namespace App\Core\Interfaces\Services;

interface ParserCbrServiceInterface
{
    /**
     * @return array
     */
    public function getHistoryAll(): array;

    /**
     * @param array $attributes
     * @return void
     */
    public function saved(array $attributes): void;

    /**
     * @return void
     */
    public function handle(): void;
}
