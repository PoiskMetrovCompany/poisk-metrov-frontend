<?php

namespace App\Services\Backup;

use App\Core\Interfaces\Services\BackupHistoryServiceInterface;
use App\Core\Interfaces\Services\BackupServiceInterface;
use App\Providers\AppServiceProvider;
use Arhitector\Yandex\Disk;
use Exception;
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

    private function backupWeb(string $toDate, string $filename, string $archiveDir, string $archivePath): void
    {
        $depthPath = 3;
        if (!is_dir($archiveDir)) {
            mkdir($archiveDir, 0755, true);
        }

        if (!empty($this->disk)) {
            try {
                $zip = new \ZipArchive();
                if ($zip->open($archivePath, \ZipArchive::CREATE) !== true) {
                    throw new Exception('Не удалось создать архив.');
                }

                $baseDir = realpath(dirname(__DIR__, $depthPath));

                $excludedPaths = [
                    realpath($baseDir . '/vendor'),
                    realpath($baseDir . '/node_modules'),
                    realpath($baseDir . '/.idea'),
                    realpath($baseDir . '/.git'),
                    realpath($baseDir . '/.run'),
                    realpath($baseDir . '/.vscode'),
                    realpath($baseDir . '/storage'),
                    realpath($baseDir . '/public/build'),
                    realpath($baseDir . '/public/galleries'),
                    realpath($baseDir . '/public/complexes'),
                    realpath($baseDir . '/public/news'),
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
                        if ($excludedPath && strpos($filePath, $excludedPath) === 0) {
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
    private function backupDB(): void
    {
        $depthPath = 3;
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');
        $toDate = date('y-m-d');
        $filename = "backup.sql";
        $path = dirname(__DIR__, $depthPath) . '/' . $filename;
        exec("mysqldump -u{$username} -p{$password} {$dbname} > {$path}");
    }

    public function execute(): void
    {
        $depthPath = 3;
        $toDate = date('y-m-d');
        $filename = "{$toDate}_.tar";
        $archiveDir = dirname(__DIR__, $depthPath);
        $archivePath = "{$archiveDir}/{$filename}";


        $this->backupHistoryService->handler(['name' => $filename, 'created_at' => $toDate, 'yaDisk' => $this->disk]);

        try {
            $this->backupDB();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->backupWeb($toDate, $filename, $archiveDir, $archivePath);

        $resource = $this->disk->getResource("Бэкапы сайта Поиск Метров/{$filename}");

        $resource->upload(dirname(__DIR__, $depthPath) . '/' . $filename);
        array_map('unlink', glob(dirname(__DIR__, $depthPath) .'/' . $filename));
        array_map('unlink', array_filter(glob(dirname(__DIR__, $depthPath) . '/' . '*.sql'), 'file_exists'));
    }
}
