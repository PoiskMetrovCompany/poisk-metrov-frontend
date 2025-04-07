<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListUserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $users = $this->userRepository->list([]);
        return new JsonResponse(
            data: UserResource::collection($users),
            status: Response::HTTP_OK
        );
    }
}
