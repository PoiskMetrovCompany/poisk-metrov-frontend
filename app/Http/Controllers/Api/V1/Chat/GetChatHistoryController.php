<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\GroupChatBotMessageRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Resources\ChatMessages\ChatHistoryResource;
use App\Models\ChatMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use OpenApi\Annotations as OA;

class GetChatHistoryController extends AbstractOperations
{
    /**
     * @param ChatServiceInterface $chatService
     * @param GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
        protected GroupChatBotMessageRepositoryInterface $groupChatBotMessageRepository,
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @OA\Get(
     *      tags={"Chat"},
     *      path="/api/v1/chats/get-history",
     *      summary="Показывает историю чата",
     *      description="Возвращение JSON объекта",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          description="ID клиента",
     *          @OA\Schema(type="integer", example="")
     *      ),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->userRepository->findById($request->input('user_id'));
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

        $collect = new ChatHistoryResource($history);


        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect->resource),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return ChatMessages::class;
    }

    public function getResourceClass(): string
    {
        return ChatHistoryResource::class;
    }
}
