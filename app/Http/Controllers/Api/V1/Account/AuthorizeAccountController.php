<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Core\Interfaces\Repositories\ManagerRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VisitedPagesController;
use App\Http\Requests\AuthorizeUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthorizeAccountController extends Controller
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
     * @param AuthorizeUserRequest $authorizeUserRequest
     * @return JsonResponse|void
     */
    public function __invoke(AuthorizeUserRequest $authorizeUserRequest)
    {
        $user = Auth::user();

        $returnData = [];

        if ($user) {
            $returnData['status'] = 'Already logged in';
            $user->connectWithManager();
            $managerForUser = $this->managerRepository->findByPhone($authorizeUserRequest->validated('phone'));

            if (
                isset($authorizeUserRequest->returnApiKey) &&
                $authorizeUserRequest->returnApiKey == 'true' &&
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

            return response()->json($returnData, 200);
        }

        $phone = $authorizeUserRequest->validated('phone');
        $pincode = $authorizeUserRequest->validated('pincode');
        $call = $this->authorizationCallRepository
            ->find(['pincode' => $pincode])
            ->find(['phone' => $phone])
            ->first();

        if ($call != null && $call->exists()) {
            $callId = $call->call_id;
            $call->delete();
        } else {
            return;
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
            //Recreate for name input
            $this->authorizationCallRepository->store([
                'pincode' => $pincode,
                'phone' => $phone,
                'call_id' => $callId
            ]);
            return response()->json(['status' => 'NeedFill'], 200);
        }

        if (! isset($user->api_token)) {
            $user->api_token = Str::random(80);
            $user->save();
        }

        Auth::loginUsingId($user->id, true);
        $this->favoritesService->syncFavoritesWithCookies();
        VisitedPagesController::syncVisitedPagesWithCookies();
        $authorizeUserRequest->session()->regenerate();

        $this->createLeadForUser($user);
        $user->syncWithLead();

        $returnData['status'] = 'Authorization success';

        $managerForUser = $this->managerRepository->findByPhone($authorizeUserRequest->validated('phone'));

        if (
            isset($authorizeUserRequest->returnApiKey) &&
            $authorizeUserRequest->returnApiKey == 'true' &&
            ($user->role == 'admin' || $managerForUser !== null)
        ) {
            $returnData['api_token'] = $user->api_token;
            //TODO: сделать нормальные роли
            $returnData['role'] = $managerForUser !== null && $user->role != 'admin' ? 'manager' : 'admin';
        }

        $user->connectWithManager();

        return response()->json($returnData, 200)->withoutCookie(Cookie::forget('chat_token'));
    }
}
