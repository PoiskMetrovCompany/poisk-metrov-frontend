<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DeleteFileController extends AbstractOperations
{
    /**
     * @param FileRequest $fileRequest
     * @return JsonResponse
     */
    function __invoke(FileRequest $request): JsonResponse
    {
        $fileUrl = $request->validated('fileUrl');
        Storage::disk('public_classic')->delete($fileUrl);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                'attributes' => [],
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_NO_CONTENT
        );
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
