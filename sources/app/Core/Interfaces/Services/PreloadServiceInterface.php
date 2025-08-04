<?php

namespace App\Core\Interfaces\Services;

interface PreloadServiceInterface
{
    /**
     * @param string $folderName
     * @return array
     */
    public function preloadFolder(string $folderName): array;
}
