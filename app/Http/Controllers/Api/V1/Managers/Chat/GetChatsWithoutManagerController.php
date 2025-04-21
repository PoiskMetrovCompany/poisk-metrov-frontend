<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetChatsWithoutManagerController extends Controller
{
    /**
     * @param ChatServiceInterface $chatService
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
    ) {
    }

    /**
     * @return JsonResponse
     */
    function __invoke(): JsonResponse
    {
        return new JsonResponse(
            data: $this->chatService->getChatsWithoutManager(),
            status: Response::HTTP_OK
        );
    }
}
