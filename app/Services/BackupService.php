<?php

namespace App\Services;

use App\Providers\AppServiceProvider;
use Arhitector\Yandex\Disk;
use App\Core\Services\BackupServiceInterface;
use BadMethodCallException;
use Exception;
use Phar;
use PharData;
use PharException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BackupService implements BackupServiceInterface
{
    private Disk $disk;
    public function __construct(Disk $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @param string $toDate
     * @param string $filename
     * @param string $archiveDir
     * @param string $archivePath
     * @return void
     */
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

                $baseDir = realpath(dirname(__DIR__, 2));

                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    $filePath = $file->getRealPath();

                    if (strpos($filePath, $baseDir) !== 0) {
                        continue;
                    }

                    if (
                        basename($filePath) === $filename ||
                        strpos($filePath, '/vendor') !== false ||
                        strpos($filePath, '/node_modules') !== false ||
                        strpos($filePath, '/.idea') !== false ||
                        strpos($filePath, '/.run') !== false ||
                        strpos($filePath, '/.vscode') !== false
                    ) {
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
        $path =  dirname(__DIR__,2) . '/' . $filename;
        // dump database
        exec("mysqldump -u{$username} -p{$password} {$dbname} > {$path}");

        // Удаление .sql
        array_map('unlink', glob(dirname(__FILE__, 2) . $filename));
    }

    public function execute(): void
    {
        $toDate = date('Y-m-d-H-i-s');
        $filename = "{$toDate}_backup.tar";
        $archiveDir = dirname(__DIR__,2);
        $archivePath = "{$archiveDir}/{$filename}";

        try {
//            $this->backupDB();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->backupWeb($toDate, $filename, $archiveDir, $archivePath);

        $resource = $this->disk->getResource($archivePath);
        if (!empty($resource->has())) {
            $resource->upload($archivePath);
        }
        array_map('unlink', glob(dirname(__FILE__, 2) . $filename));
    }
}
