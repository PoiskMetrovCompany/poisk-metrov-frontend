<?php

namespace App\Services;

/**
 * Class PreloadService.
 */
class PreloadService
{
    public function preloadFolder(string $folderName): array
    {
        $resourcePath = resource_path($folderName);
        $fullFilePaths = array_merge_recursive($this->getFilesFromFolder($resourcePath));
        $fileResoucesPaths = [];

        foreach ($fullFilePaths as $fullFilePath) {
            $resourcesStart = strpos($fullFilePath, 'resources');
            $fileResoucesPaths[] = substr($fullFilePath, $resourcesStart);
        }

        return $fileResoucesPaths;
    }

    private function getFilesFromFolder(string $folderName): array
    {
        $folderContent = glob("{$folderName}" . DIRECTORY_SEPARATOR . '*');
        $files = [];

        foreach ($folderContent as $fileOrFolder) {
            if (is_dir($fileOrFolder)) {
                $subFolderContents = $this->getFilesFromFolder($fileOrFolder);
                $files = array_merge($files, $subFolderContents);
            } else {
                $files[] = $fileOrFolder;
            }
        }

        return $files;
    }
}
