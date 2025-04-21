<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagerMessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SendMessageToSessionController extends Controller
{
    /**
     * @param ChatServiceInterface $chatService
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
    ) {
    }

    /**
     * @param ManagerMessageRequest $request
     * @return JsonResponse
     */
    function __invoke(ManagerMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $text = $validated['message'];
        $apiToken = $validated['apiToken'];
        $chatToken = $validated['chatToken'];
        $this->chatService->sendMessageToSession($text, $apiToken, $chatToken);

        return new JsonResponse(
            data: [],
            status: Response::HTTP_CREATED
        );
    }
}
