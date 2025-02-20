<?php

namespace App\Services;

/**
 * Class FileService.
 */
class FileService
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
}
