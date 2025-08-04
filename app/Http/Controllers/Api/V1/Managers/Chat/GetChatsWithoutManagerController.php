<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Resources\ChatMessages\ChatMessagesResource;
use App\Models\ChatMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class GetChatsWithoutManagerController extends AbstractOperations
{
    /**
     * @param ChatServiceInterface $chatService
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
    ) {
    }

    /**
     * @OA\Get(
     *       tags={"Chat"},
     *       path="/api/v1/chats/read-without",
     *       summary="читать без менеджера",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param Request $request
     * @return JsonResponse
     */
    function __invoke(Request $request): JsonResponse
    {
        $collect = new ChatMessagesResource($this->chatService->getChatsWithoutManager());

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
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
