<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateFilesController extends Controller
{
    /**
     * @param FileUploadRequest $fileUploadRequest
     * @return JsonResponse
     */
    public function uploadFiles(FileUploadRequest $fileUploadRequest): JsonResponse
    {
        $files = $fileUploadRequest->allFiles();
        $filePath = $fileUploadRequest->validated('filePath');

        foreach ($files as $file) {
            $targetPath = $filePath;

            foreach ($file as $fileInside) {
                $originalName = $fileInside->getClientOriginalName();
                Storage::disk('public_classic')->putFileAs($targetPath, $fileInside, $originalName);
            }
        }

        return new JsonResponse(
            data: [],
            status: Response::HTTP_OK
        );
    }
}
