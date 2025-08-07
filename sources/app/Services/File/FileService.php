<?php

namespace App\Services\File;

use App\Core\Interfaces\Services\FileServiceInterface;

/**
 * @package App\Services\File
 * @implements FileServiceInterface
 */
final class FileService implements FileServiceInterface
{
    public function getFileAsBase64(string|null $URL): string|null
    {
        if (isset($URL) && trim($URL) != '') {
            $ch = curl_init($URL);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            //TODO: make sure 0 always means there is a file
            if ($responseCode == 200 || $responseCode == 0) {
                return base64_encode(file_get_contents($URL));
            }
        }

        return null;
    }

    public function fileInfo(array $attributes): array
    {
        // TODO: реализовать получение файла
        return [];
    }
}
