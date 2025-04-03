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
    public function execute(): void;
}
