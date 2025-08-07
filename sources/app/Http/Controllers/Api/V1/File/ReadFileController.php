<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadFileController extends AbstractOperations
{
    /**
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes(['file' => $this->getFilesFromFolder(public_path())]),
                ...self::metaData($request, $request->all())
            ]
        );
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

    public function getEntityClass(): string
    {
        return File::class;
    }

    public function getResourceClass(): string
    {
        return FileResource::class;
    }
}
