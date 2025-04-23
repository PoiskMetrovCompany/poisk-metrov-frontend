<?php

namespace App\Http\Controllers\Api\V1\File\Folders;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FolderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CreateFolderController extends AbstractOperations
{
    /**
     * @param FileRequest $request
     * @return JsonResponse
     */
    function __invoke(FileRequest $request): JsonResponse
    {
        $folderUrl = $request->validated('fileUrl');

        if (Storage::disk('public_classic')->directoryExists($folderUrl)) {
            return  new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes([]),
                    ...self::metaData($request, $request->all())
                ],
                status: Response::HTTP_CREATED
            );
        }

        Storage::disk('public_classic')->makeDirectory($folderUrl);

        return  new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([]),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function getEntityClass(): string
    {
        return 'Folder';
    }

    public function getResourceClass(): string
    {
        return FolderResource::class;
    }
}
