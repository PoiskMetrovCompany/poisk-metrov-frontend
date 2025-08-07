<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetChatHistoryController extends Controller
{
    /**
     * @param ChatServiceInterface $chatService
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $chatToken = $request['chatToken'];

        return new JsonResponse(
            data: $this->chatService->getManagerChatHistory($chatToken),
            status: Response::HTTP_OK
        );
    }
}
