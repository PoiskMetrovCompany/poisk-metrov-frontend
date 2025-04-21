<?php

namespace App\Http\Controllers\Api\V1\Managers\Chat;

use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Services\ChatServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetChatsController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    function __invoke(Request $request): JsonResponse
    {
        $managerId = $this->managerRepository->findById($request->managerId)->id;
        return new JsonResponse(
            data: $this->chatService->getChats($managerId),
            status: Response::HTTP_OK
        );
    }
}
