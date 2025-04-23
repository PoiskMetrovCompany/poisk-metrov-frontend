<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFilesController extends AbstractOperations
{
    /**
     * @param FileRequest $fileRequest
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function __invoke(FileRequest $request)
    {
        $fileUrl = $request->validated('fileUrl');

        return response()
            ->download(public_path($fileUrl))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
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
