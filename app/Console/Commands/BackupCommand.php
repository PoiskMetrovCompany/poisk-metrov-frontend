<?php

namespace App\Console\Commands;

use App\Services\Backup\BackupHistoryService;
use App\Services\Backup\BackupService;
use Arhitector\Yandex\Disk;
use Illuminate\Console\Command;

/**
 * @see BackupService::execute
 */
class BackupCommand extends Command
{
    /** * @var string */
    protected $signature = 'app:backup';

    /**
     * @return void
     */
    public function handle(): void
    {
        $backupService = new BackupService(
            app(Disk::class),
            app(BackupHistoryService::class)
        );
        $backupService->execute();
    }
}
