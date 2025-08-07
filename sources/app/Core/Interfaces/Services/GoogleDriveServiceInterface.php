<?php

namespace App\Core\Interfaces\Services;

use Google\Service\Drive\DriveFile;
use Google\Service\Drive\FileList;
use Psr\Http\Message\StreamInterface;

interface GoogleDriveServiceInterface
{
    /**
     * @param string $fileId
     * @return StreamInterface
     */
    public function getFile(string $fileId): StreamInterface;

    /**
     * @param string $filePath
     * @param string $fileName
     * @param array $folderIds
     * @return DriveFile
     */
    public function uploadFile(string $filePath, string $fileName, array $folderIds = []): DriveFile;

    /**
     * @param string $folderName
     * @param array $inFolders
     * @return DriveFile
     */
    public function createFolder(string $folderName, array $inFolders = []): DriveFile;

    /**
     * @param string $folderId
     * @return DriveFile
     */
    public function getFolder(string $folderId): DriveFile;

    /**
     * @param string $folderId
     * @return FileList
     */
    public function getFileListFromFolder(string $folderId): FileList;

    /**
     * @param string $documentId
     * @return mixed
     */
    public function getDocument(string $documentId);

    /**
     * @param string $sheetId
     * @param string $range
     * @return array
     */
    public function getSheetData(string $sheetId, string $range): array;

    /**
     * @param string $sheetId
     * @param array $rowData
     * @param string $range
     * @return void
     */
    public function addRowToSheet(string $sheetId, array $rowData, string $range): void;

}
