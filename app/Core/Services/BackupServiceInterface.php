<?php
namespace App\Core\Services;

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
