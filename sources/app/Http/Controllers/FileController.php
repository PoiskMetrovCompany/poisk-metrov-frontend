<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\FileUploadRequest;
use Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileController extends Controller
{
    /**
     * @return array
     */
    function getPublicFiles()
    {
        return $this->getFilesFromFolder(public_path());
    }

    /**
     * @param FileRequest $fileRequest
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function getFile(FileRequest $fileRequest)
    {
        $fileUrl = $fileRequest->validated('fileUrl');

        return response()->download(public_path($fileUrl))->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }

    /**
     * @param FileUploadRequest $fileUploadRequest
     * @return void
     */
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

    /**
     * @param FileRequest $fileRequest
     * @return void
     */
    function deleteFile(FileRequest $fileRequest)
    {
        $fileUrl = $fileRequest->validated('fileUrl');

        Storage::disk('public_classic')->delete($fileUrl);
    }

    /**
     * @param FileRequest $folderRequest
     * @return void
     */
    function createFolder(FileRequest $folderRequest)
    {
        $folderUrl = $folderRequest->validated('fileUrl');

        if (Storage::disk('public_classic')->directoryExists($folderUrl)) {
            return;
        }

        Storage::disk('public_classic')->makeDirectory($folderUrl);
    }

    /**
     * @param FileRequest $folderRequest
     * @return void
     */
    function deleteFolder(FileRequest $folderRequest)
    {
        $folderUrl = $folderRequest->validated('fileUrl');

        if (! Storage::disk('public_classic')->directoryExists($folderUrl)) {
            return;
        }

        Storage::disk('public_classic')->deleteDirectory($folderUrl);
    }

    /**
     * @param string $folderName
     * @return array
     */
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
