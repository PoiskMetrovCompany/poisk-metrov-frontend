<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\FileUploadRequest;
use Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileController extends Controller
{
    function getPublicFiles()
    {
        return $this->getFilesFromFolder(public_path());
    }

    function getFile(FileRequest $fileRequest)
    {
        $fileUrl = $fileRequest->validated('fileUrl');

        return response()->download(public_path($fileUrl))->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }

    function uploadFiles(FileUploadRequest $fileUploadRequest)
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
    }

    function deleteFile(FileRequest $fileRequest)
    {
        $fileUrl = $fileRequest->validated('fileUrl');

        Storage::disk('public_classic')->delete($fileUrl);
    }

    function createFolder(FileRequest $folderRequest)
    {
        $folderUrl = $folderRequest->validated('fileUrl');

        if (Storage::disk('public_classic')->directoryExists($folderUrl)) {
            return;
        }

        Storage::disk('public_classic')->makeDirectory($folderUrl);
    }

    function deleteFolder(FileRequest $folderRequest)
    {
        $folderUrl = $folderRequest->validated('fileUrl');

        if (! Storage::disk('public_classic')->directoryExists($folderUrl)) {
            return;
        }

        Storage::disk('public_classic')->deleteDirectory($folderUrl);
    }

    function getFilesFromFolder(string $folderName): array
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
