<?php

namespace App\Core\Interfaces\Services;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface ChatServiceInterface
{
    /**
     * @param Model $session
     * @return void
     */
    public function sendSessionToCRM(Model $session): void;

    /**
     * @param array $sessionHistory
     * @param Model|null $user
     * @param bool $withMarkdown
     * @return array
     */
    public function getSessionHistoryAsString(array $sessionHistory, Model|null $user, bool $withMarkdown = true): array;

    /**
     * @param string $chatToken
     * @param bool $withMarkdown
     * @param bool $noSizeLimit
     * @return string
     */
    public function getChatHistoryAsString(string $chatToken, bool $withMarkdown = true, bool $noSizeLimit = false): string;

    /**
     * @param Model $session
     * @param Model|null $user
     * @return array
     */
    public function getMessagesFromSession(Model $session, Model|null $user): array;

    /**
     * @param string $chatToken
     * @param ?Model $user
     * @param bool $nonDeletedOnly
     * @return array
     */
    public function getChatHistory(string $chatToken, ?Model $user, bool $nonDeletedOnly = false): array;

    /**
     * @param string $userName
     * @param string $message
     * @param string $chatToken
     * @param Model $session
     * @param int|null $userId
     * @return void
     */
    public function sendChatMessage(string $userName, string $message, string $chatToken, Model $session, int $userId = null): void;

    /**
     * @param string $userName
     * @param string $message
     * @param string $chatToken
     * @param string|null $group
     * @return void
     */
    public function sendGroupMessage(string $userName, string $message, string $chatToken, string|null $group): void;

    /**
     * @param $chatToken
     * @return array
     */
    public function getManagerChatHistory($chatToken): array;

    /**
     * @param string $chatToken
     * @param string $userName
     * @param string $message
     * @param string $date
     * @return array
     */
    public function getNewChatData(string $chatToken, string $userName, string $message, string $date): array;

    /**
     * @param $managerId
     * @param $chatToken
     */
    public function tryStartSession($managerId, $chatToken);

    /**
     * @param string $message
     * @param string $apiToken
     * @param string $chatToken
     * @return void
     */
    public function sendMessageToSession(string $message, string $apiToken, string $chatToken): void;

    /**
     * @param $managerId
     * @return array
     */
    public function getChats($managerId): array;

    /**
     * @return array
     */
    public function getChatsWithoutManager(): array;
}
