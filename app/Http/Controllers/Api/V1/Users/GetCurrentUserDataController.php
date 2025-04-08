<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class GetCurrentUserDataController extends Controller
{
    /**
     * @OA\Schema(
     *      schema="User/GetCurrent",
     *      @OA\Property(
     *          property="status",
     *          type="string"
     *      ),
     *  	@OA\Property(
     *        property="error",
     *        type="string"
     *      )
     * ),
     *
     * @OA\Get(
         * tags={"User"},
         * path="/api/v1/users/get-current",
         * summary="Получение информации о текущем авторизованном пользователе.",
         * description="Возвращение JSON объекта",
         * @OA\Response(
             * response=200,
             * description="УСПЕХ!",
         * ),
         * @OA\Response(
             * response=404,
             * description="Resource not found"
         * )
     * )
     *
    * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user != null) {
            return new JsonResponse(
                data: [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'surname' => $user['surname'],
                    'patronymic' => $user['patronymic'],
                    'email' => $user['email'],
                    'password' => ''
                ],
                status: Response::HTTP_OK,
            );
        }
        return new JsonResponse(data: [],status: Response::HTTP_OK);
    }
}
