<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadFilesController extends Controller
{
    /**
     * @param FileRequest $fileRequest
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function __invoke(FileRequest $fileRequest)
    {
        $fileUrl = $fileRequest->validated('fileUrl');

        return response()
            ->download(public_path($fileUrl))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
