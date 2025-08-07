<?php

namespace App\Services\Backup;

use App\Core\Interfaces\Services\BackupHistoryServiceInterface;
use Illuminate\Support\Facades\Storage;

/**
 * @package App\Services\Backup
 * @see AppServiceProvider::registerBackupHistoryService()
 * @implements BackupHistoryServiceInterface
 * @property-read static $filePath
 */
final class BackupHistoryService implements BackupHistoryServiceInterface
{
    protected static $filePath = 'backups.json';

    private function setHistory(array $attributes): void
    {
        if (!Storage::disk('local')->exists(self::$filePath)) {
            Storage::disk('local')->put(self::$filePath, json_encode([]));
        }

        $content = self::getHistoryAll();

        $content[] = [
            'name' => $attributes['name'],
            'created_at' => $attributes['created_at'],
        ];

        if (count($content) > 5) {
            $oldestFile = $content[0]['name'];
            $attributes['yaDisk']->getResource(config('yandexdisk.disk.path').$oldestFile)->delete();
            array_shift($content);
        }

        Storage::disk('local')->put(self::$filePath, json_encode($content, JSON_PRETTY_PRINT));
    }

    private function getHistoryAll(): array
    {
        $content = Storage::disk('local')->get(self::$filePath);
        return json_decode($content, true);
    }

    public function handler(array $attributes): void
    {
        if (!Storage::disk('local')->exists(self::$filePath)) {
            Storage::disk('local')->put(self::$filePath, json_encode([]));
        }

        $this->setHistory($attributes);
    }
}
