<?php

namespace App\Core\Interfaces\Services;

interface FileServiceInterface
{
    /**
     * @param array $attributes
     * @return array
     */
    public function fileInfo(array $attributes): array;
}
