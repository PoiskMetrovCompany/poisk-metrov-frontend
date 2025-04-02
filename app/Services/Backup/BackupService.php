<?php

namespace App\Services\Backup;

use App\Core\Interfaces\Services\BackupHistoryServiceInterface;
use App\Core\Interfaces\Services\BackupServiceInterface;
use App\Providers\AppServiceProvider;
use Arhitector\Yandex\Disk;
use Exception;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @see Disk
 * @see BackupHistoryServiceInterface
 * @see AppServiceProvider::registerBackupService()
 * @implements BackupServiceInterface
 */
final class BackupService implements BackupServiceInterface
{
    private Disk $disk;
    private BackupHistoryServiceInterface $backupHistoryService;

    public function __construct(Disk $disk, BackupHistoryServiceInterface $backupHistoryService)
    {
        $this->disk = $disk;
        $this->backupHistoryService = $backupHistoryService;
    }

    public function backupWeb(string $toDate, string $filename, string $archiveDir, string $archivePath): void
    {
        if (!is_dir($archiveDir)) {
            mkdir($archiveDir, 0755, true);
        }

        if (!empty($this->disk)) {
            try {
                $zip = new \ZipArchive();
                if ($zip->open($archivePath, \ZipArchive::CREATE) !== true) {
                    throw new Exception('Не удалось создать архив.');
                }

                $baseDir = realpath(dirname(__DIR__, 3));

                $excludedPaths = [
                    '/vendor',
                    '/node_modules',
                    '/.idea',
                    '/.run',
                    '/.vscode',
                    $filename,
                ];

                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    $filePath = $file->getRealPath();

                    if (strpos($filePath, $baseDir) !== 0) {
                        continue;
                    }

                    $shouldExclude = false;
                    foreach ($excludedPaths as $excludedPath) {
                        if (strpos($filePath, $excludedPath) !== false) {
                            $shouldExclude = true;
                            break;
                        }
                    }

                    if ($shouldExclude) {
                        continue;
                    }

                    $relativePath = substr($filePath, strlen($baseDir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }

                $zip->close();

            } catch (\Exception $e) {
                throw new Exception('Ошибка при создании архива: ' . $e->getMessage());
            }
        }
    }
    public function backupDB(): void
    {
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');
        $toDate = date('Y-m-d-H-i-s');
        $filename = "{$toDate}_backup.sql";
        $path =  dirname(__DIR__,3) . '/' . $filename;
        exec("mysqldump -u{$username} -p{$password} {$dbname} > {$path}");
    }

    public function execute(): void
    {
        $toDate = date('Y-m-d-H-i-s');
        $filename = "{$toDate}_backup.tar";
        $archiveDir = dirname(__DIR__, 3);
        $localArchivePath = "{$archiveDir}/{$filename}";
        $remoteFilePath = "Бэкапы сайта Поиск Метров/{$filename}";

        try {
            $this->backupDB();
            $this->backupWeb($toDate, $filename, $archiveDir, $localArchivePath);
            $resource = $this->disk->getResource($remoteFilePath);
            $this->uploadFile($resource, $localArchivePath);

            array_map('unlink', glob(dirname(__DIR__, 3) . '/' . '*_backup.sql'));
            unlink($localArchivePath);

        } catch (\Exception $e) {
            throw new \Exception("Ошибка при выполнении бэкапа: " . $e->getMessage());
        }
    }

    private function uploadFile($resource, $filePath): void
    {
        $fileSize = filesize($filePath);

        try {
            $resource->upload($filePath);
        } catch (\Exception $e) {
            throw new \Exception("Ошибка при загрузке файла на Yandex.Disk: " . $e->getMessage());
        }
    }
}
