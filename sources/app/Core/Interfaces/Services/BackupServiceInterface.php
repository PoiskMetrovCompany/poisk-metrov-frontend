<?php
namespace App\Core\Interfaces\Services;

use App\Services\Backup\BackupService;

/**
 * @see BackupService
 */
interface BackupServiceInterface
{
    /**
     * @return void
     */
    public function backupDB(): void;

    /**
     * @param string $toDate
     * @param string $filename
     * @param string $archiveDir
     * @param string $archivePath
     * @return void
     */
    public function backupWeb(string $toDate, string $filename, string $archiveDir, string $archivePath): void;

    /**
     * @return void
     */
    public function execute(): void;
}
