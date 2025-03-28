<?php

namespace App\Core\Interfaces\Services;

use App\Models\ChatSession;
use App\Models\User;

interface ChatServiceInterface
{
    /**
     * @param ChatSession $session
     * @return void
     */
    public function sendSessionToCRM(ChatSession $session): void;

    /**
     * @param array $sessionHistory
     * @param User|null $user
     * @param bool $withMarkdown
     * @return array
     */
    public function getSessionHistoryAsString(array $sessionHistory, User|null $user, bool $withMarkdown = true): array;

    /**
     * @param string $chatToken
     * @param bool $withMarkdown
     * @param bool $noSizeLimit
     * @return string
     */
    public function getChatHistoryAsString(string $chatToken, bool $withMarkdown = true, bool $noSizeLimit = false): string;

    /**
     * @param ChatSession $session
     * @param User|null $user
     * @return array
     */
    public function getMessagesFromSession(ChatSession $session, User|null $user): array;

    /**
     * @param string $chatToken
     * @param $user
     * @param bool $nonDeletedOnly
     * @return array
     */
    public function getChatHistory(string $chatToken, $user, bool $nonDeletedOnly = false): array;

    /**
     * @param string $userName
     * @param string $message
     * @param string $chatToken
     * @param ChatSession $session
     * @param int|null $userId
     * @return void
     */
    public function sendChatMessage(string $userName, string $message, string $chatToken, ChatSession $session, int $userId = null): void;

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
