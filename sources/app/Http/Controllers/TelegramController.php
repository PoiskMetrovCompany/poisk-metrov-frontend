<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Http\Requests\TelegramCallbackRequest;
use App\Models\Manager;
use App\Models\User;
use App\Services\ChatService;
use App\Services\TelegramService;
use App\Services\TextService;
use App\Telegram\TelegramResponse;
use Illuminate\Support\Facades\Log;
use Storage;

// TODO: ИГНОРИРОВАТЬ РЕФАКТОРИНГ!!!!
class TelegramController extends Controller
{
    private $chatConfig;

    public function __construct(
        protected TelegramService $telegramService,
        protected ChatServiceInterface $chatService,
        protected TextServiceInterface $textService
    ) {
        $this->chatConfig = Storage::json('chat-config.json');
    }

    public function callbackRegister(TelegramCallbackRequest $callback)
    {
        $callbackToTelegramResponse = [];
        $callbackToTelegramResponse['ok'] = true;
        $callbackToTelegramResponse['result'] = $callback->validated('message');
        $telegramResponse = new TelegramResponse(json_decode(json_encode($callbackToTelegramResponse)));
        $userId = $telegramResponse->result->from->id;

        if ($telegramResponse->result->contact != null) {
            $phone = $this->textService->formatPhone($telegramResponse->result->contact->phoneNumber);
            $manager = Manager::where(['phone' => $phone])->first();
            $user = User::where(['phone' => $phone])->first();

            if ($user != null) {
                $user->connectWithManager();
            }

            if ($manager != null) {
                $manager->update(['telegram_id' => $telegramResponse->result->contact->userId]);
                $inviteLink = $this->telegramService->createChatInviteLink($this->chatConfig[$manager->city], $manager->city);
                $parameters['reply_markup'] = [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => 'Перейти в группу',
                                'url' => $inviteLink->result->invite_link
                            ]
                        ]
                    ]
                ];

                $this->telegramService->sendMessage('Вы успешно зарегистрированы!', $telegramResponse->result->contact->userId, $parameters);
            } else {
                $this->telegramService->sendMessage('Вашего номера нет в списке сотрудников.', $userId);
            }

            return;
        }

        if ($telegramResponse->result->text == null) {
            return;
        }

        $messageText = trim($telegramResponse->result->text);

        if (! isset($telegramResponse->result->text)) {
            return;
        }

        if (str_starts_with($messageText, '/start')) {
            $possibleManager = Manager::where(['telegram_id' => $userId])->first();

            if ($possibleManager != null) {
                $user = User::where(['phone' => $possibleManager->phone])->first();

                if ($user != null) {
                    $user->connectWithManager();
                }

                $inviteLink = $this->telegramService->createChatInviteLink($this->chatConfig[$possibleManager->city], $possibleManager->city);
                $parameters['reply_markup'] = [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => 'Перейти в группу',
                                'url' => $inviteLink->result->invite_link
                            ]
                        ]
                    ]
                ];
                $this->telegramService->sendMessage('Вы уже авторизованы.', $userId, $parameters);

                return;
            }

            $parameters['reply_markup'] = [
                'keyboard' => [
                    [
                        [
                            'text' => 'Поделиться контактом',
                            'request_contact' => true
                        ]
                    ]
                ]
            ];

            $this->telegramService->sendMessage('Для авторизации в чате предоставьте номер телефона.', $userId, $parameters);

            return;
        }
    }
}
