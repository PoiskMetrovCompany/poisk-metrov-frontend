<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class UpdateAccountController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserAdsAgreementRepositoryInterface $userAdsAgreementRepository
    )
    {

    }

    /**
     * @OA\Schema(
     *       schema="User/Account/Update",
     *       @OA\Property(
     *           property="status",
     *           type="string"
     *       ),
     *   	@OA\Property(
     *         property="error",
     *         type="string"
     *       )
     *  ),
     *
     * @OA\Post(
     *      tags={"UserAccount"},
     *      path="/api/v1/users/account/update-profile/",
     *      summary="Обновление профиля",
     *      description="Возвращение JSON объекта",
     *      @OA\Response(
     *          response=201,
     *          description="УСПЕХ!",
     *          @OA\JsonContent(
     *              @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
     *              @OA\Property(property="name", type="string", example="Иван"),
     *              @OA\Property(property="surname", type="string", example="Поляков"),
     *              @OA\Property(property="patronymic", type="string", example="Олегович"),
     *              @OA\Property(property="email", type="string", example="my.example@mail.ru")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     *  )
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $account = $request->validated();

        if (Auth::user()->phone != $account->phone) {
            return new JsonResponse(
                data: [
                    'status' => 'Unauthorized',
                ],
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        $repository = $this->userRepository->findByPhone($account->phone);
        $repository->update($account);
        $this->userAdsAgreementRepository->findByPhone($account->phone)->update(['name' => $account->name]);

        return new JsonResponse(
            data: [
                'user' => new UserResource($repository),
                'status' => 'User updated',
            ],
            status: Response::HTTP_OK
        );
    }
}
