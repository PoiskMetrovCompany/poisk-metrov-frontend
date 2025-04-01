<?php

namespace App\Core\Interfaces\Services;

interface FileServiceInterface
{
    /**
     * @param array $attributes
     * @return array
     */
    public function fileInfo(array $attributes): array;

    /**
     * @param string|null $URL
     * @return string|null
     */
    public function getFileAsBase64(string|null $URL): string|null;
}
