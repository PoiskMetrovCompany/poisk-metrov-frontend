<?php

namespace App\Services;

use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Core\Interfaces\Repositories\ChatTokenCRMLeadPairRepositoryInterface;
use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerChatMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\UserChatMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\CRMServiceInterface;
use App\Events\ChatUpdated;
use App\Events\ManagerMessage;
use App\Events\UserMessage;
use App\Models\ChatSession;
use App\Models\GroupChatBotMessage;
use App\Models\User;
use App\Telegram\InlineButtons\ReplyToClientButton;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * @package App\Services
 * @implements ChatServiceInterface
 * @property-read int $maxMessageLength
 * @property-read TelegramService $telegramService
 * @property-read CRMServiceInterface $crmService
 * @property-read ManagerRepositoryInterface $managerRepository
 * @property-read ChatTokenCRMLeadPairRepositoryInterface $chatTokenCRMLeadPairRepository
 * @property-read UserRepositoryInterface $userRepository
 * @property-read ChatSessionRepositoryInterface $chatSessionRepository
 * @property-read UserChatMessageRepositoryInterface $userChatMessageRepository
 * @property-read GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository
 * @property-read ManagerChatMessageRepositoryInterface $managerChatMessageRepository
 */
class ChatService implements ChatServiceInterface
{
    // TODO: в некоторых методах следует убрать обращения к моделям, оставить это до следующей итерации рефакторинга

    private int $maxMessageLength = 4096;

    public function __construct(
        protected TelegramService $telegramService,
        protected CRMServiceInterface $crmService,
        protected ManagerRepositoryInterface $managerRepository,
        protected ChatTokenCRMLeadPairRepositoryInterface $chatTokenCRMLeadPairRepository,
        protected UserRepositoryInterface $userRepository,
        protected ChatSessionRepositoryInterface $chatSessionRepository,
        protected UserChatMessageRepositoryInterface $userChatMessageRepository,
        protected GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository,
        protected ManagerChatMessageRepositoryInterface $managerChatMessageRepository
    ) {
    }

    public function sendSessionToCRM(ChatSession $session): void
    {
        $user = $session->getUser();
        $sessionHistory = $this->getMessagesFromSession($session, $user);
        $sessionHistory = $this->getSessionHistoryAsString($sessionHistory, $user, false)[0];
        $sessionHistory = "Сеанс общения\r\n{$sessionHistory}";

        if ($user) {
            $this->crmService->createLead($user->phone, $sessionHistory, $user->crm_city, $user->name);
        } else if (config('app.send_chat_without_auth')) {
            $manager = $this->managerRepository->findById($session->manager_id);
            //Функция выполняется из команды где нет кук, поэтому город определяем по городу оператора чата
            $city = $manager->city;
            $result = json_decode($this->crmService->createLead('', $sessionHistory, $city, 'Посетитель'));
            $chatToken = $session->chat_token;
            $tokenLeadPairData = ['chat_token' => $chatToken, 'crm_id' => $result->id, 'crm_city' => $city];

            if (!$this->userRepository->isExists(['chat_token' => $chatToken])) {
                $this->chatTokenCRMLeadPairRepository->store($tokenLeadPairData);
            }
        }
    }

    public function getSessionHistoryAsString(array $sessionHistory, User|null $user, bool $withMarkdown = true): array
    {
        $messageSeparator = '----------------';
        $totalMessageCount = 0;
        $sessionHistoryAsString = '';

        foreach ($sessionHistory as $messageInChat) {
            $authorName = '';
            $message = $messageInChat['message'];

            if (isset($messageInChat['authorName'])) {
                $authorName = $messageInChat['authorName'];
            }

            if ($messageInChat['author'] == 'user') {
                if ($user) {
                    $authorName = $user->name;
                } else {
                    $authorName = 'Посетитель';
                }
            }

            if ($authorName != '') {
                if ($withMarkdown) {
                    $authorName = "<b>$authorName</b>\r\n\r\n";
                } else {
                    $authorName = "$authorName\r\n\r\n";
                }
            }

            $sessionHistoryAsString = "{$sessionHistoryAsString}\r\n$messageSeparator\r\n{$authorName}{$message}";
            $totalMessageCount++;
        }

        return [$sessionHistoryAsString, $totalMessageCount];
    }

    public function getChatHistoryAsString(string $chatToken, bool $withMarkdown = true, bool $noSizeLimit = false): string
    {
        $user = $this->userRepository->findByChatToken($chatToken);
        $historyMessage = '';
        $clientHistory = $this->getChatHistory($chatToken, $user);
        $messageSeparator = '----------------';
        $totalMessageCount = 0;

        foreach ($clientHistory as $sessionHistory) {
            $historyAndMessageCount = $this->getSessionHistoryAsString($sessionHistory, $user, $withMarkdown);
            $historyMessage .= $historyAndMessageCount[0];
            $totalMessageCount += $historyAndMessageCount[1];
        }

        if ($noSizeLimit) {
            return $historyMessage;
        }

        $shownMessageCount = 0;
        $why = "Показаны последние {$totalMessageCount} сообщений из {$totalMessageCount}\r\n";

        while ((strlen($historyMessage) + strlen($why)) > $this->maxMessageLength) {
            $historyMessage = explode($messageSeparator, $historyMessage);
            array_shift($historyMessage);
            $shownMessageCount = count($historyMessage);
            $why = "Показаны последние {$shownMessageCount} сообщений из {$totalMessageCount}\r\n";
            $historyMessage = implode($messageSeparator, $historyMessage);
        }

        $historyMessage = "{$why}{$historyMessage}";

        return $historyMessage;
    }

    public function getMessagesFromSession(ChatSession $session, User|null $user): array
    {
        $userMessages = $session->clientMessages;
        $managerMessages = $session->managerMessages;
        $messagesInSession = (new Collection($userMessages))->merge($managerMessages)->sortBy('created_at')->values();

        $managerName = '';
        $managerProfilePic = '';

        if (count($managerMessages) > 0) {
            $managerId = $managerMessages[0]->manager_id;

            if ($managerId != null) {
                $managerName = $this->userRepository->findById($managerId)->name;
            }
        }

        foreach ($messagesInSession as $message) {
            $message->author = "user";
            $message->authorName = '';

            if (isset($message->manager_id)) {
                $message->author = "manager";
                $message->authorName = $managerName;
                $message->profilePic = $managerProfilePic;
            } else {
                $message->authorName = 'Посетитель';
            }

            if (isset($message->chat_token) && $user) {
                $message->authorName = $user->name;
            }
        }

        $messagesInSession = $messagesInSession->map->only(['author', 'authorName', 'message', 'created_at', 'profilePic']);

        return $messagesInSession->toArray();
    }

    public function getChatHistory(string $chatToken, $user, bool $nonDeletedOnly = false): array
    {
        if ($nonDeletedOnly) {
            $allSessions = $this->chatSessionRepository->list(['chat_token' => $chatToken]);
        } else {
            $allSessions = $this->chatSessionRepository->withTrashedList(['chat_token' => $chatToken]);
        }

        $history = [];

        foreach ($allSessions as $session) {
            $messagesInSession = $this->getMessagesFromSession($session, $user);

            if (count($messagesInSession) > 0) {
                array_push($history, ...$messagesInSession);
            }
        }

        return $history;
    }

    public function sendChatMessage(string $userName, string $message, string $chatToken, ChatSession $session, int $userId = null): void
    {
        // $sentMessage = "$userName\r\n\r\n$message";
        // $this->telegramService->sendMessage($sentMessage, $session->manager_telegram_id);
        //Для обновления сессии
        $session->touch();
        $this->userChatMessageRepository->store([
            'chat_session_id' => $session->id,
            'message' => $message,
            'chat_token' => $chatToken
        ]);

        $newMessageEvent = new UserMessage($userName, $chatToken, $message);

        broadcast($newMessageEvent);

        $date = Carbon::now()->format('Y/m/d H:i:s');
        $chatData = $this->getChatData($chatToken, $userName, $message, $date, 'active');
        $chatUpdatedEvent = new ChatUpdated($chatToken, $chatData, false);

        broadcast($chatUpdatedEvent);
    }

    public function sendGroupMessage(string $userName, string $message, string $chatToken, string|null $group): void
    {
        if ($group != null) {
            $sentMessage = "$userName\r\n\r\n$message";
            $parameters = [];
            $parameters['reply_markup'] = [
                'inline_keyboard' => [
                    [
                        (new ReplyToClientButton('poisk_metrov_test_bot', $chatToken, $group))->toArray()
                    ]
                ]
            ];
            $response = $this->telegramService->sendMessage($sentMessage, $group, $parameters);
            $newGroupChatMessageParameters = ['sender_chat_token' => $chatToken, 'message' => $message, 'message_id' => $response->result->messageId, 'group_id' => $group];
            $this->groupChatBotMessageRepository->store($newGroupChatMessageParameters);
        } else {
            $newGroupChatMessageParameters = ['sender_chat_token' => $chatToken, 'message' => $message, 'message_id' => 0, 'group_id' => 0];
            $this->groupChatBotMessageRepository->store($newGroupChatMessageParameters);
        }

        $date = Carbon::now()->format('Y/m/d H:i:s');
        $chatData = $this->getChatData($chatToken, $userName, $message, $date, 'missed');
        $chatUpdatedEvent = new ChatUpdated($chatToken, $chatData, true);

        broadcast($chatUpdatedEvent);
    }

    public function getManagerChatHistory($chatToken): array
    {
        $user = $this->userRepository->findByChatToken($chatToken);
        $messages = $this->getChatHistory($chatToken, $user);
        $countMessages = count($messages);
        $startDateString = $this->userChatMessageRepository->list($chatToken)->min('created_at');
        $startDate = new \DateTime($startDateString);
        $currDate = new \DateTime();
        $daysFromStart = $currDate->diff($startDate)->days;
        $stats['name'] = $user ? "$user->name $user->surname" : 'Посетитель';
        $stats['started'] = $daysFromStart;
        $stats['messages_number'] = $countMessages;
        $result['stats'] = $stats;
        $result['messages'] = $messages;

        return $result;
    }

    private function getChatData(string $chatToken, string $userName, string $message, string $date, string $status): array
    {
        $user = $this->userRepository->findByChatToken($chatToken);
        $history = $this->getChatHistory($chatToken, $user);
        $countMessages = count($history);

        $result = [
            'name' => $userName,
            'message' => $message,
            'time' => $date,
            'count' => $countMessages,
            'status' => $status
        ];

        return $result;
    }

    public function getNewChatData(string $chatToken, string $userName, string $message, string $date): array
    {
        $userMessages = $this->groupChatBotMessageRepository->list(['sender_chat_token' => $chatToken]);
        $countMessages = count($userMessages);

        $result = [
            'name' => $userName,
            'message' => $message,
            'time' => $date,
            'count' => $countMessages,
            'status' => 'missed'
        ];

        return $result;
    }

    public function tryStartSession($managerId, $chatToken)
    {
        $session = $this->chatSessionRepository->findByChatToken($chatToken);

        if ($session != null && $session->manager_id != $managerId) {
            return 'Session already started';
        }

        $userName = 'Посетитель';
        $user = $this->getUserForToken($chatToken);

        if ($user != null) {
            $userName = $user->name;
        }

        $newSessionParameters = [
            'chat_token' => $chatToken,
            'manager_id' => $managerId,
        ];

        $session = $this->chatSessionRepository->store($newSessionParameters);
        $messagesInGroups = $this->groupChatBotMessageRepository->list(['sender_chat_token' => $chatToken]);

        foreach ($messagesInGroups as $message) {
            if ($message->group_id != 0 && $message->message_id != 0) {
                $this->telegramService->editMessage("<b>ОТВЕЧЕНО</b>\r\n\r\n{$userName}\r\n\r\n{$message->message}", $message->message_id, $message->group_id, ['parse_mode' => 'HTML']);
                $this->telegramService->editMessageReplyMarkup($message->message_id, $message->group_id, []);
            }

            $userMessage = $this->userChatMessageRepository->store([
                'chat_session_id' => $session->id,
                'chat_token' => $message->sender_chat_token,
                'message' => $message->message
            ]);
            // TODO: тут пока непонятно как организовать репозиторий, оставлю обращение от модели
            $userMessage->update(['created_at' => $message->created_at]);
            $message->delete();
            // END
        }
    }

    // TODO: нет смысла держать это в сервисах ИСКОРЕНИТЬ!!!
    private function getUserForToken(string $chatToken)
    {
        return $this->userRepository->findByChatToken($chatToken);
    }

    public function sendMessageToSession(string $message, string $apiToken, string $chatToken): void
    {
        $manager = $this->userRepository->findByApiToken($apiToken);

        if ($manager == null) {
            return;
        }

        $managerUserId = $manager->id;
        $manager = $this->managerRepository->findByUserId($managerUserId);

        if ($manager == null) {
            return;
        }

        $managerName = $manager->document_name;
        $managerProfilePic = $manager->avatar_file_name;
        $session = ChatSession::where('chat_token', $chatToken)->latest('created_at')->first();

        /*
         * TODO: нет понимания как организовать репозиторий для этого обращения
         * $session = $this->chatSessionRepository->findByChatToken($chatToken);
         * но надо сделать как то сверх гибко.
         * ВЕРНУТЬСЯ ВО ВТОРОЙ ИТЕРАЦИИ РЕФАКТОРИНГА!!!
         */

        if ($session == null) {
            $session = $this->chatSessionRepository->store([
                'chat_token' => $chatToken,
                'manager_id' => $managerUserId,
            ]);
        }

        $newMessageEvent = new ManagerMessage(
            $session->chat_token,
            $message,
            $managerProfilePic,
            $managerName
        );

        $this->managerChatMessageRepository->store([
            'chat_session_id' => $session->id,
            'message' => $message,
            'manager_id' => $managerUserId,
            'manager_telegram_id' => null
        ]);

        $session->touch();

        broadcast($newMessageEvent);
    }

    public function getChats($managerId): array
    {
        // $sessions = ChatSession::where('manager_id', $managerId)->orderBy('created_at')->get()->unique('chat_token');
        /*
         * TODO: нет понимания как организовать репозиторий для этого обращения
         * $session = $this->chatSessionRepository->findByChatToken($chatToken);
         * но надо сделать как то сверх гибко.
         * ВЕРНУТЬСЯ ВО ВТОРОЙ ИТЕРАЦИИ РЕФАКТОРИНГА!!!
         */
        $sessions = ChatSession::withTrashed()->where('manager_id', $managerId)->orderBy('created_at', 'desc')->get()->unique('chat_token');
        $result = [];

        foreach ($sessions as $session) {
            $item = [];
            $user = $this->userRepository->findByChatToken($session->chat_token);
            $history = $this->getChatHistory($session->chat_token, $user);

            if (count($history) == 0) {
                //Somehow it's possible to have empty history
                \Log::info("History for {$session->id} is empty");

                continue;
            }

            $item['message'] = $history[count($history) - 1]['message'];
            $item['name'] = $user ? $user->name : 'Посетитель';
            $item['time'] = $history[count($history) - 1]['created_at'];
            $item['status'] = $session->deleted_at ? 'finished' : '';
            $item['count'] = count($history);
            $item['chatToken'] = $session->chat_token;
            $result[] = $item;
        }

        return $result;
    }

    public function getChatsWithoutManager(): array
    {
        /*
         * TODO: нет понимания как организовать репозиторий для этого обращения
         * $session = $this->chatSessionRepository->findByChatToken($chatToken);
         * но надо сделать как то сверх гибко.
         * ВЕРНУТЬСЯ ВО ВТОРОЙ ИТЕРАЦИИ РЕФАКТОРИНГА!!!
         */
        $chatsWithoutManager = GroupChatBotMessage::orderBy('created_at')->get()->unique('sender_chat_token');
        $result = [];

        foreach ($chatsWithoutManager as $chat) {
            $item = [];
            $user = $this->userRepository->findByChatToken($chat->sender_chat_token);
            /*
             * TODO: нет понимания как организовать репозиторий для этого обращения
             * $session = $this->chatSessionRepository->findByChatToken($chatToken);
             * но надо сделать как то сверх гибко.
             * ВЕРНУТЬСЯ ВО ВТОРОЙ ИТЕРАЦИИ РЕФАКТОРИНГА!!!
             */
            $history = GroupChatBotMessage::where('sender_chat_token', $chat->sender_chat_token)->orderBy('created_at')->get();
            $item['message'] = $history[count($history) - 1]->message;
            $item['name'] = $user ? $user->name : 'Посетитель';
            $item['time'] = $history[count($history) - 1]->created_at;
            $item['status'] = 'missed';
            $item['count'] = count($history);
            $item['chatToken'] = $chat->sender_chat_token;
            $result[] = $item;
        }

        return $result;
    }
}
