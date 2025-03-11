<?php

namespace App\Console\Commands;

use App\Core\Services\BackupServiceInterface;
use App\Services\BackupService;
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
            app(Disk::class)
        );
        $backupService->execute();
    }
}
