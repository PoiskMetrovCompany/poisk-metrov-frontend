<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessages\ChatMessagesResource;
use App\Models\ChatMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class GetChatsController extends AbstractOperations
{
    /**
     * @param ChatServiceInterface $chatService
     * @param ManagerRepositoryInterface $managerRepository
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
        protected ManagerRepositoryInterface $managerRepository,
    ) {
    }

    /**
     * @OA\Get(
     *       tags={"Chat"},
     *       path="/api/v1/chats/read",
     *       summary="читать чат",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="manager_id",
     *          in="query",
     *          required=true,
     *          description="ID менеджера",
     *          @OA\Schema(type="integer", example="")
     *       ),
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
        $managerId = $this->managerRepository->findById($request->manager_id)->id;
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($this->chatService->getChats($managerId)),
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
