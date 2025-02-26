<?php

namespace App\Services;

use App\Models\Manager;
use Illuminate\Support\Facades\Storage;

/**
 * Class ManagersService
 */
class ManagersService extends AbstractService
{
    private $chatConfig;

    public function __construct(protected TelegramService $telegramService)
    {
        $this->chatConfig = Storage::json('chat-config.json');
    }

    public function deleteManager(Manager $manager)
    {
        if ($manager->autokick_immune == 1) {
            echo "Will not kick {$manager->document_name}" . PHP_EOL;
        }

        if ($manager->telegram_id != null && $manager->autokick_immune != 1 && $this->chatConfig != null) {
            foreach ($this->chatConfig as $key => $value) {
                switch ($key) {
                    case 'novosibrsk':
                    case 'st-petersburg':
                    case 'defaultGroup':
                        $this->telegramService->unbanChatMember($value, $manager->telegram_id);
                }
            }
        }

        $manager->delete();
    }

    public function getManagerNames(): array
    {
        return $this->getManagersList()->pluck('document_name')->sort()->toArray();
    }

    public function getManagersList()
    {
        return Manager::all();
    }

    public static function getFromApp(): ManagersService
    {
        return parent::getFromApp();
    }
}