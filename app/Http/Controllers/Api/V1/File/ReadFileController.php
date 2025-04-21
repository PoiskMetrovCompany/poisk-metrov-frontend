<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReadFileController extends Controller
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return $this->getFilesFromFolder(public_path());
    }

    // TODO: Вот "это" вынести в сервисы
    /**
     * @param string $folderName
     * @return array
     */
    private function getFilesFromFolder(string $folderName): array
    {
        $folderContent = glob("{$folderName}" . DIRECTORY_SEPARATOR . '*');
        $files = [];

        foreach ($folderContent as $fileOrFolder) {
            if (is_dir($fileOrFolder)) {
                $subFolderContents = $this->getFilesFromFolder($fileOrFolder);
                $split = explode('/', $fileOrFolder);
                $folderName = $split[count($split) - 1];
                $files[$folderName] = $subFolderContents;
            } else {
                array_unshift($files, $fileOrFolder);
            }
        }

        return $files;
    }
}
