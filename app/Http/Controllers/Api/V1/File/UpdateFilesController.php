<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateFilesController extends AbstractOperations
{
    /**
     * @param FileUploadRequest $fileUploadRequest
     * @return JsonResponse
     */
    public function uploadFiles(FileUploadRequest $request): JsonResponse
    {
        $files = $request->allFiles();
        $filePath = $request->validated('filePath');

        foreach ($files as $file) {
            $targetPath = $filePath;

            foreach ($file as $fileInside) {
                $originalName = $fileInside->getClientOriginalName();
                Storage::disk('public_classic')->putFileAs($targetPath, $fileInside, $originalName);
            }
        }

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([]),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
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
