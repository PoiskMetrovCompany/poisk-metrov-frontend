<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Core\Interfaces\Repositories\ChatSessionRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientMessageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class SendChatMessageController extends Controller
{
    private mixed $chatConfig;


    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected ChatSessionRepositoryInterface $chatSession,
        protected CityServiceInterface $cityService,
        protected ChatServiceInterface $chatService,
    ) {
        $this->chatConfig = Storage::json('chat-config.json');
    }

    /**
     * @param ClientMessageRequest $request
     * @return JsonResponse
     */
    public function sendChatMessage(ClientMessageRequest $request): JsonResponse
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
                data: [],
                status: Response::HTTP_CREATED
            );
        }
    }
}
