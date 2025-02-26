<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagerMessageRequest;
use App\Models\Manager;
use App\Services\ChatService;
use App\Services\TelegramService;
use App\Services\TextService;
use Illuminate\Http\Request;

class ManagerChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService,
        protected TelegramService $telegramService,
        protected TextService $textService
    ) {
    }

    public function getChatHistory(Request $request)
    {
        $chatToken = $request['chatToken'];

        return $this->chatService->getManagerChatHistory($chatToken);
    }

    public function tryStartSession(Request $request)
    {
        $userId = $request['id'];
        $managerId = Manager::where('user_id', $userId)->first()->id;
        $chatToken = $request['chatToken'];

        return $this->chatService->tryStartSession($managerId, $chatToken);
    }

    function sendMessageToSession(ManagerMessageRequest $request)
    {
        $validated = $request->validated();
        $text = $validated['message'];
        $apiToken = $validated['apiToken'];
        $chatToken = $validated['chatToken'];

        return $this->chatService->sendMessageToSession($text, $apiToken, $chatToken);
    }

    function getChats(Request $request)
    {
        $managerId = Manager::where('user_id', $request->managerId)->first()->id;

        return $this->chatService->getChats($managerId);
    }

    function getChatsWithoutManager()
    {
        return $this->chatService->getChatsWithoutManager();
    }
}