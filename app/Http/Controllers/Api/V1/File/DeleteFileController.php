<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DeleteFileController extends Controller
{
    /**
     * @param FileRequest $fileRequest
     * @return JsonResponse
     */
    function __invoke(FileRequest $fileRequest): JsonResponse
    {
        $fileUrl = $fileRequest->validated('fileUrl');
        Storage::disk('public_classic')->delete($fileUrl);

        return new JsonResponse(
            data: [],status:
            Response::HTTP_NO_CONTENT
        );
    }
}
