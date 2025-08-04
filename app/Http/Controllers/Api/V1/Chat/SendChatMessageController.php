<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientMessageRequest;
use App\Http\Resources\ChatMessages\ChatMessagesResource;
use App\Models\ChatMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class SendChatMessageController extends AbstractOperations
{
    private mixed $chatConfig;


    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected ChatSessionRepositoryInterface $chatSession,
        protected CityServiceInterface $cityService,
        protected ChatServiceInterface $chatService,
    ) {
        // TODO: сделать выборку из монги
        $this->chatConfig = Storage::json('chat-config.json');
    }

    /**
     * @OA\Post(
     *       tags={"Chat"},
     *       path="/api/v1/chats/send-message",
     *       summary="Отправка сообщеничя в чат",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="1"),
     *              @OA\Property(property="chatCategory", type="string", example="..."),
     *          )
     *        ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param ClientMessageRequest $request
     * @return JsonResponse
     */
    public function __invoke(ClientMessageRequest $request): JsonResponse
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

            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes([]),
                    ...self::metaData($request, $request->all())
                ],
                status: Response::HTTP_CREATED
            );
        }
    }

    public function getEntityClass(): string
    {
        return ChatMessages::class;
    }

    public function getResourceClass(): string
    {
        return ChatMessagesResource::class;
    }
}
