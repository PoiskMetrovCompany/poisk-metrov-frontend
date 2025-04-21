<?php

namespace App\Http\Controllers\Api\V1\File\Folders;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class CreateFolderController extends Controller
{
    /**
     * @param FileRequest $folderRequest
     * @return JsonResponse
     */
    function __invoke(FileRequest $folderRequest): JsonResponse
    {
        $folderUrl = $folderRequest->validated('fileUrl');

        if (Storage::disk('public_classic')->directoryExists($folderUrl)) {
            return  new JsonResponse(
                data: [],
                status: Response::HTTP_CREATED
            );
        }

        Storage::disk('public_classic')->makeDirectory($folderUrl);

        return  new JsonResponse(
            data: [],
            status: Response::HTTP_CREATED
        );
    }
}
