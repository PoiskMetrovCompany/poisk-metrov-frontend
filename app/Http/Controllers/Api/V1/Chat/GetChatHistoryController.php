<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class GetChatHistoryController extends Controller
{
    /**
     * @param ChatServiceInterface $chatService
     * @param GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
        protected GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository,
    ) {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
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

        return new JsonResponse(
            data: ['history' => $history],
            status: Response::HTTP_OK
        );
    }
}
