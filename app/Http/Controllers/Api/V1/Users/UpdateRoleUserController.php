<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Interfaces\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateRoleUserController extends Controller
{
    public function __construct(protected UserServiceInterface $userService)
    {

    }

    /**
     * @param UpdateRoleRequest $request
     * @return void
     */
    public function __invoke(UpdateRoleRequest $request): JsonResponse
    {
        $id = $request->validated('id');
        $role = $request->validated('role');

        $service = $this->userService->updateRole($id, $role);
        return new JsonResponse(
            data: new UserResource($service),
            status: Response::HTTP_CREATED
        );
    }
}
