<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Core\Common\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VisitedPagesController;
use App\Http\Requests\AuthorizeUserRequest;
use App\Http\Resources\Account\AccountResource;
use App\Models\User;
use Cookie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @see AppServiceProvider::registerAuthorizationCallRepository()
 * @see AppServiceProvider::registerManagerRepository()
 * @see AppServiceProvider::registerUserRepository()
 * @see AppServiceProvider::registerFavoritesService()
 * @see AuthorizationCallRepositoryInterface
 * @see ManagerRepositoryInterface
 * @see UserRepositoryInterface
 * @see FavoritesServiceInterface
 */
class AuthorizationAccountController extends AbstractOperations
{
    public function __construct(
        protected AuthorizationCallRepositoryInterface $authorizationCallRepository,
        protected ManagerRepositoryInterface $managerRepository,
        protected UserRepositoryInterface $userRepository,
        protected FavoritesServiceInterface $favoritesService
    )
    {

    }

    /**
     * @OA\Post(
     * tags={"UserAccount"},
     * path="/api/v1/users/account/authorization/",
     * summary="Авторизация профиля",
     * description="Возвращение JSON объекта",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
     * @OA\Property(property="code", type="string", example="код из СМС")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="УСПЕХ!",
     * @OA\JsonContent(
     * @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
     * @OA\Property(property="pincode", type="string", example="код из СМС")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found",
     * @OA\JsonContent(
     * @OA\Property(property="error", type="string", example="Resource not found")
     * )
     * )
     * )
     *
     * @param AuthorizeUserRequest $authorizeUserRequest
     * @return JsonResponse
     */
    public function __invoke(AuthorizeUserRequest $request): JsonResponse
    {
        $user = Auth::user();

        $returnData = [];

        if (!empty($user)) {
            $returnData['status'] = 'Already logged in';
            $user->connectWithManager();
            $managerForUser = $this->managerRepository->findByPhone($request->validated('phone'));

            if (
                isset($request->returnApiKey) &&
                $request->returnApiKey == 'true' &&
                ($user->role == 'admin' || $managerForUser !== null)
            ) {
                if (! isset($user->api_token)) {
                    $user->api_token = Str::random(80);
                    $user->save();
                }

                $returnData['api_token'] = $user->api_token;
                //TODO: сделать нормальные роли
                $returnData['role'] = $managerForUser !== null && $user->role != 'admin' ? 'manager' : 'admin';
            }

            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes($returnData),
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_OK
            );
        }

        $phone = $request->validated('phone');
        $pincode = $request->validated('pincode');
        $call = $this->authorizationCallRepository
            ->find(['pincode' => $pincode])
            ->find(['phone' => $phone])
            ->first();

        if ($call != null && $call->exists()) {
            $callId = $call->call_id;
            $call->delete();
        } else {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes([123]),
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_OK
            );
        }

        $user = $this->userRepository->findByPhone($phone);
        $userExists = $user != null;
        $hasName = false;

        if ($userExists) {
            $hasName = $user->name != null;
        } else {
            $user = $this->userRepository->store(['phone' => $phone]);
        }

        if (! $userExists || ! $hasName) {
            $this->authorizationCallRepository->store([
                'pincode' => $pincode,
                'phone' => $phone,
                'call_id' => $callId
            ]);
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes(['status' => 'NeedFill']),
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_OK
            );
        }

        if (! isset($user->api_token)) {
            $user->api_token = Str::random(80);
            $user->save();
        }

        Auth::loginUsingId($user->id, true);
        $this->favoritesService->syncFavoritesWithCookies();
        VisitedPagesController::syncVisitedPagesWithCookies();
        $request->session()->regenerate();

        $this->createLeadForUser($user);
        $user->syncWithLead();

        $returnData['status'] = 'Authorization success';

        $managerForUser = $this->managerRepository->findByPhone($request->validated('phone'));

        if (
            isset($authorizeUserRequest->returnApiKey) &&
            $authorizeUserRequest->returnApiKey == 'true' &&
            ($user->role == RoleEnum::Admin->value || $managerForUser !== null)
        ) {
            $returnData['api_token'] = $user->api_token;
            //TODO: сделать нормальные роли
            $returnData['role'] = $managerForUser !== null && $user->role != RoleEnum::Admin->value
                ? RoleEnum::Manager->value
                : RoleEnum::Admin->value;
        }

        $user->connectWithManager();

        return response()->json(
            data: [
                ...self::identifier(),
                ...self::attributes($returnData), // TODO: респонс что то должен возвращать
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        )->withoutCookie(
            Cookie::forget('chat_token')
        );
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getResourceClass(): string
    {
        return AccountResource::class;
    }
}
