<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Http\Requests\ClientMessageRequest;
use App\Models\ChatSession;
use App\Models\GroupChatBotMessage;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Repositories\UserRepository;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Storage;

/**
 * @see AppServiceProvider::registerChatService()
 * @see AppServiceProvider::registerCityService()
 * @see AppServiceProvider::registerUserRepository()
 * @see AppServiceProvider::registerChatSessionRepository()
 * @see AppServiceProvider::registerGroupChatBotMessageRepository()
 * @see TelegramService
 * @see ChatServiceInterface
 * @see CityServiceInterface
 * @see UserRepositoryInterface
 * @see ChatSessionRepositoryInterface
 * @see GroupChatBotMessageRepositoryInterface
 */
class ChatController extends Controller
{
    private $chatConfig;

    /**
     * @param TelegramService $telegramService
     * @param ChatServiceInterface $chatService
     * @param CityServiceInterface $cityService
     * @param UserRepositoryInterface $userRepository
     * @param ChatSessionRepositoryInterface $chatSession
     * @param GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository
     */
    public function __construct(
        protected TelegramService $telegramService,
        protected ChatServiceInterface $chatService,
        protected CityServiceInterface $cityService,
        protected UserRepositoryInterface $userRepository,
        protected ChatSessionRepositoryInterface $chatSession,
        protected GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository,
    ) {
        $this->chatConfig = Storage::json('chat-config.json');
    }

    /**
     * @param ClientMessageRequest $request
     * @return void
     */
    public function sendChatMessage(ClientMessageRequest $request)
    {
        $validated = $request->validated();
        $message = $validated['message'];
        $user = $request->user();
        $userName = 'Посетитель';
        $chatToken = '';

        if ($user) {
            $chatToken = $user->chat_token;
            $userName = "{$user->name} {$user->surname}";
        } else {
            $chatToken = Cookie::get('chat_token');
            $user = $this->userRepository->findByChatToken($chatToken);

            if ($user) {
                $userName = "{$user->name} {$user->surname}";
            }
        }

        $session = $this->chatSession->findByChatToken($chatToken);

        if ($session) {
            $this->chatService->sendChatMessage($userName, $message, $chatToken, $session);
        } else if ($chatToken !== '' && $chatToken) {
            if ($this->chatConfig != null) {
                $group = $request->validated('chatCategory');

                if ($group) {
                    $currentCity = $this->cityService->getUserCity();
                    $group = $this->chatConfig[$currentCity];
                } else {
                    $group = $this->chatConfig['defaultGroup'];
                }
            } else {
                $group = null;
            }

            $this->chatService->sendGroupMessage($userName, $message, $chatToken, $group);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChatHistory(Request $request)
    {
        $user = $request->user();
        $chatToken = null;
        $history = [];

        if ($user) {
            $chatToken = $user->chat_token;
        } else {
            $chatToken = Cookie::get('chat_token');
        }

        if ($chatToken) {
            $history = new Collection($this->chatService->getChatHistory($chatToken, $user));
            //Костыль - в сессиях GroupChatBotMessage пересоздается как обычное сообщение
            //Но если сессия не началась, то пользователь не увидит свои сообщения если перезагрузит страницу т.к. они не принадлежат никакой сессии и не появляются в истории
            $orphanMessages = $this->groupChatBotMessageRepository->find(['sender_chat_token' => $chatToken])->get();

            if ($orphanMessages->count() > 0) {
                foreach ($orphanMessages as &$orphanMessage) {
                    $orphanMessage->chat_session_id = $orphanMessage->sender_chat_token;
                    $orphanMessage->author = 'user';

                    if ($user && isset($user->name)) {
                        $orphanMessage->authorName = $user->name;
                    } else {
                        $orphanMessage->authorName = 'Посетитель';
                    }
                }

                $orphanMessages = $orphanMessages->map->only(['author', 'authorName', 'message', 'created_at'])->toArray();
                $history = $history->merge($orphanMessages)->sortBy('created_at')->values();
            }
        }

        return response()->json(['history' => $history]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserChatToken(Request $request)
    {
        $user = $request->user();
        //Этот кук доступен только на бэке (secure = true), поэтому с фронта делаем запрос сюда и получаем кук
        $chatTokenCookie = Cookie::get('chat_token');

        //Если токен есть
        if ($chatTokenCookie) {
            //Если юзер авторизован, но токена нет, то даем ему текущий токен
            if ($user) {
                if (! $user->chat_token) {
                    $user->update(['chat_token' => $chatTokenCookie]);
                }

                $chatTokenCookie = $user->chat_token;
            }

            //К этому моменту у юзера точно есть токен, но если он был другой, то все равно переназначаем
            //чтобы когда сессия истечет он не появлися бы как новый пользователь
            $chatTokenCookieObject = Cookie::make('chat_token', $chatTokenCookie, time() + 31536000, '/', null, true);

            return response()->json(['hasToken' => true, 'token' => $chatTokenCookie])->withCookie($chatTokenCookieObject);
        }

        //Если токена нет, то надо создать
        $token = '';

        //Берем токен у авторизованного пользователя
        if ($user) {
            //Если токена нет, то создаем, затем берем
            if (! $user->chat_token) {
                $user->update(['chat_token' => Str::random(32)]);
            }

            $token = $user->chat_token;
        } else {
            //Просто делаем токен. При авторизации он запишется в поле пользователя
            $token = Str::random(32);
        }

        $chatTokenCookie = Cookie::make('chat_token', $token, time() + 31536000, '/', null, true);

        return response()->json(['hasToken' => true, 'token' => $token])->withCookie($chatTokenCookie);
    }
}
