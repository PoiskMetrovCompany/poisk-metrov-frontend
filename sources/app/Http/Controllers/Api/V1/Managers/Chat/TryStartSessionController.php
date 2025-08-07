<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagerMessageRequest;
use App\Http\Resources\ChatMessages\ChatMessagesResource;
use App\Models\ChatMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class TryStartSessionController extends AbstractOperations
{
    /**
     * @param ChatServiceInterface $chatService
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
    ) {
    }

    /**
     * @OA\Post(
     *       tags={"Chat"},
     *       path="/api/v1/chats/try-start-session",
     *       summary="Создать сессию для клиента",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="1"),
     *              @OA\Property(property="chatToken", type="string", example="..."),
     *              @OA\Property(property="apiToken", type="string", example="..."),
     *          )
     *        ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param ManagerMessageRequest $request
     * @return JsonResponse
     */
    public function __invoke(ManagerMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $text = $validated['message'];
        $apiToken = $validated['apiToken'];
        $chatToken = $validated['chatToken'];
        $this->chatService->sendMessageToSession($text, $apiToken, $chatToken);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes([]),
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
        return ChatMessagesResource::class;
    }
}
