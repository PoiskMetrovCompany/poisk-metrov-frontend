<?php

namespace App\Services;

use App\Core\Interfaces\Services\GoogleDriveServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use Google\Model;
use Google\Service\Docs;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\FileList;
use Google\Service\Sheets;
use Google_Client;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\StreamInterface;
use Google_Service_Sheets_ValueRange;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements GoogleDriveServiceInterface
 * @property-read Google_Client $googleClient
 * @property-read Drive $driveService
 * @property-read Docs $docsService
 * @property-read Sheets $sheetService
 * @property-read string $configFileName
 * @property-read string $defaultFileFields
 * @property-read TextServiceInterface $textService
 */
class GoogleDriveService extends AbstractService implements GoogleDriveServiceInterface
{
    private Google_Client $googleClient;
    private Drive $driveService;
    private Docs $docsService;
    private Sheets $sheetService;
    private string $configFileName = 'google-client-config.json';
    private string $defaultFileFields = 'contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents';

    public function __construct(
        private TextServiceInterface $textService
    ) {
        $pathToConfig = Storage::path($this->configFileName);
        putenv("GOOGLE_APPLICATION_CREDENTIALS={$pathToConfig}");
        $apiKey = config('google.api_key');
        $this->googleClient = new Google_Client();
        $this->googleClient->setDeveloperKey($apiKey);
        $this->googleClient->useApplicationDefaultCredentials();
        $this->googleClient->addScope(Drive::DRIVE);
        $this->googleClient->addScope(Docs::DRIVE);
        $this->googleClient->addScope(Sheets::SPREADSHEETS);
        $this->driveService = new Drive($this->googleClient);
        $this->docsService = new Docs($this->googleClient);
        $this->sheetService = new Sheets($this->googleClient);
    }

    public function getFile(string $fileId): StreamInterface
    {
        //alt=media возвращает StreamInterface, но IntelliSense этого не знает
        return $this->driveService->files->get($fileId, ['alt' => 'media'])->getBody();
    }

    public function uploadFile(string $filePath, string $fileName, array $folderIds = []): DriveFile
    {
        $file = new DriveFile();
        $file->setName($fileName);
        $file->setParents($folderIds);
        $mimeType = mime_content_type($filePath);
        $data = file_get_contents($filePath);
        $file->setMimeType($mimeType);

        return $this->driveService->files->create($file, [
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => $this->defaultFileFields,
        ]);
    }

    public function createFolder(string $folderName, array $inFolders = []): DriveFile
    {
        $file = new DriveFile();
        $file->setName($folderName);
        $file->setParents($inFolders);
        $file->setMimeType('application/vnd.google-apps.folder');

        return $this->driveService->files->create($file);
    }

    public function getFolder(string $folderId): DriveFile
    {
        return $this->driveService->files->get($folderId);
    }

    public function getFileListFromFolder(string $folderId): FileList
    {
        $optParams = [
            'pageSize' => 100,
            'fields' => "nextPageToken, files({$this->defaultFileFields})",
            'q' => "'{$folderId}' in parents"
        ];

        return $this->driveService->files->listFiles($optParams);
    }

    public function getDocument(string $documentId)
    {
        return $this->driveService->files->export($documentId, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')->getBody();
    }

    public function getSheetData(string $sheetId, string $range): array
    {
        $data = $this->sheetService->spreadsheets_values->get($sheetId, $range);

        return $data->getValues();
    }

    public function addRowToSheet(string $sheetId, array $rowData, string $range): void
    {
        foreach ($rowData as &$data) {
            if ($data == null || $data == '') {
                $data = Model::NULL_VALUE;
            }
        }

        $values = [$rowData];
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];

        $this->sheetService->spreadsheets_values->append($sheetId, "$range!A1", $body, $params);
    }

    public static function getFromApp(): GoogleDriveService
    {
        return parent::getFromApp();
    }
}
