<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;


class UpdateRoleUserController extends AbstractOperations
{
    public function __construct(protected UserServiceInterface $userService)
    {

    }

    /**
     * @OA\Schema(
        * schema="User/UpdateRole",
         * @OA\Property(
             * property="status",
             * type="string"
         * ),
         * @OA\Property(
             * property="error",
             * type="string"
         * )
         * ),
         *
         * @OA\Post(
             * tags={"User"},
             * path="/api/v1/users/update-role",
             * summary="обновление роли пользователя.",
             * description="Возвращение JSON объекта",
         * @OA\Response(
             * response=200,
             * description="УСПЕХ!",
     *     @OA\JsonContent(
                * @OA\Property(property="id", type="integer", example="1"),
                * @OA\Property(property="role", type="string", example="Менеджер")
        * )
         * ),
         * @OA\Response(
             * response=404,
             * description="Resource not found"
         * )
     * )
     *
    * @param UpdateRoleRequest $request
    * @return JsonResponse
     */
    public function __invoke(UpdateRoleRequest $request): JsonResponse
    {
        $id = $request->validated('id');
        $role = $request->validated('role');
        $service = $this->userService->updateRole($id, $role);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($service),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getResourceClass(): string
    {
        return UserResource::class;
    }
}
