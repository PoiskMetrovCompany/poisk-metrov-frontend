<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\UserRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class RegisterProfileController extends AbstractOperations
{
    public function __construct(
        protected UserRepository $userRepository,
    )
    {

    }

    /**
     * @OA\Post(
     * tags={"Auth"},
     * path="/api/v1/auth/registration/",
     * summary="Регистрация профиля",
     * description="Возвращает информацию о профиле",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
     * @OA\Property(property="name", type="string", example="Иван"),
     * @OA\Property(property="surname", type="string", example="Колосков"),
     * @OA\Property(property="city", type="string", example="novosibirsk")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="УСПЕХ!",
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found"
     * )
     * )
     *
    * /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(UserRegistrationRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $attributes['role'] = 'client';
        $attributes['key'] = Str::uuid()->toString();

        $user = $this->userRepository->store($attributes);
        $collect = UserResource::make($user);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
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
