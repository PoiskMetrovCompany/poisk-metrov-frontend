<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Http\Requests\ManagerMessageRequest;
use App\Models\Manager;
use App\Providers\AppServiceProvider;
use App\Services\TelegramService;
use Illuminate\Http\Request;

/**
 * @see AppServiceProvider::registerChatService()
 * @see AppServiceProvider::registerTextService()
 * @see ChatServiceInterface
 * @see TextServiceInterface
 */
class ManagerChatController extends Controller
{
    /**
     * @param ChatServiceInterface $chatService
     * @param TelegramService $telegramService
     * @param TextServiceInterface $textService
     * @param ManagerRepositoryInterface $managerRepository
     */
    public function __construct(
        protected ChatServiceInterface $chatService,
        protected TelegramService $telegramService,
        protected TextServiceInterface $textService,
        protected ManagerRepositoryInterface $managerRepository,
    ) {
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getChatHistory(Request $request)
    {
        $chatToken = $request['chatToken'];

        return $this->chatService->getManagerChatHistory($chatToken);
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function tryStartSession(Request $request)
    {
        $userId = $request['id'];
        $managerId = $this->managerRepository->findById($userId)->id;
        $chatToken = $request['chatToken'];

        return $this->chatService->tryStartSession($managerId, $chatToken);
    }

    /**
     * @param ManagerMessageRequest $request
     * @return null
     */
    function sendMessageToSession(ManagerMessageRequest $request)
    {
        $validated = $request->validated();
        $text = $validated['message'];
        $apiToken = $validated['apiToken'];
        $chatToken = $validated['chatToken'];

        return $this->chatService->sendMessageToSession($text, $apiToken, $chatToken);
    }

    /**
     * @param Request $request
     * @return array
     */
    function getChats(Request $request)
    {
        $managerId = $this->managerRepository->findById($request->managerId)->id;
        return $this->chatService->getChats($managerId);
    }

    /**
     * @return array
     */
    function getChatsWithoutManager()
    {
        return $this->chatService->getChatsWithoutManager();
    }
}
