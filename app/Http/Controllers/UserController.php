<?php

namespace App\Http\Controllers;

use App\CRM\Commands\CreateLead;
use App\CRM\Commands\GetLead;
use App\CRM\Commands\UpdateLead;
use App\Http\Requests\AuthorizeUserRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\AuthorizationCall;
use App\Models\ChatTokenCRMLeadPair;
use App\Models\Manager;
use App\Models\User;
use App\Models\UserAdsAgreement;
use App\Services\CityService;
use App\Services\FavoritesService;
use App\Services\UserService;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Validation\UnauthorizedException;

class UserController extends Controller
{
    public function __construct(
        protected FavoritesService $favoritesService,
        protected CityService $cityService,
        protected UserService $userService
    ) {
    }

    public function updateUser(UpdateUserRequest $updateUserRequest)
    {
        $name = $updateUserRequest->validated('name');
        $phone = $updateUserRequest->validated('phone');
        $surname = $updateUserRequest->validated('surname');

        $userModel = User::where('phone', $phone)->first();

        if ($userModel->id != Auth::id()) {
            throw new UnauthorizedException();
        }

        $userModel->update(['name' => $name]);

        if ($surname != null) {
            $userModel->update(['surname' => $surname]);
        }

        return response()->json(['status' => 'User updated'], 200);
    }

    public function authorizeUser(AuthorizeUserRequest $authorizeUserRequest)
    {
        $user = Auth::user();

        $returnData = [];

        if ($user) {
            $returnData['status'] = 'Already logged in';
            $user->connectWithManager();
            $managerForUser = Manager::where('phone', $authorizeUserRequest->validated('phone'))->first();

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
        $call = AuthorizationCall::where('pincode', $pincode)->where('phone', $phone)->first();

        if ($call != null && $call->exists()) {
            $callId = $call->call_id;
            $call->delete();
        } else {
            return;
        }

        $user = User::where('phone', $phone)->first();
        $userExists = $user != null;
        $hasName = false;

        if ($userExists) {
            $hasName = $user->name != null;
        } else {
            $user = User::create(['phone' => $phone]);
        }

        if (! $userExists || ! $hasName) {
            //Recreate for name input
            AuthorizationCall::create(['pincode' => $pincode, 'phone' => $phone, 'call_id' => $callId]);
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

        $managerForUser = Manager::where('phone', $phone)->first();

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

    public function createLeadForUser(User $user)
    {
        $city = $this->cityService->getUserCity();

        if ($user->crm_id != null) {
            $getLead = new GetLead($user->crm_id, $city);
            $leadText = $getLead->execute();
            $lead = json_decode($leadText);

            //Если лид был удален, то надо создать нового
            if (
                ! $lead ||
                //Написано в документации
                $lead->message == 'Лид с данным id не найден' ||
                //На самом деле
                $lead->message == 'Лид не найден'
            ) {
                $user->update(['crm_id' => null, 'crm_city' => null]);
                $this->createLeadForUser($user);
            }

            return;
        }

        $chatToken = (app()->make(ChatController::class)->getUserChatToken(request()))->getData()->token;
        $leadForChatToken = ChatTokenCRMLeadPair::where(['chat_token' => $chatToken])->first();

        if ($chatToken && $leadForChatToken != null) {
            $user->update(['crm_id' => $leadForChatToken->crm_id, 'crm_city' => $city]);

            $nameInput = new \stdClass();
            $nameInput->input_id = 1;
            $nameInput->value = $user->getFullName();
            $nameInput->value_type_id = 1;
            $phoneInput = new \stdClass();
            $phoneInput->input_id = 2;
            $phoneInput->value = $user->phone;
            $phoneInput->value_type_id = 1;

            $updateLead = new UpdateLead($leadForChatToken->crm_id, [
                $nameInput,
                $phoneInput
            ], $city);
            $updateLead->execute();

            return;
        }

        $createLead = new CreateLead($user->getFullName(), $user->phone, '', $city);
        $lead = $createLead->execute(false, false);

        if (! $lead) {
            return;
        }

        //parent_id - айди первой заявки от контакта (телефона). Работаем с ним если он существует
        if (isset($lead->parent_id)) {
            $user->update(['crm_id' => $lead->parent_id, 'crm_city' => $city]);
        } else {
            $user->update(['crm_id' => $lead->id, 'crm_city' => $city]);
        }
    }

    public function logOut(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Auth::logout();
            $request->session()->regenerate();
            return response()->json(['status' => 'Log out success'], 200);
        } else {
            return response()->json(['status' => 'User not logged in'], 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $updateProfileRequest)
    {
        $name = $updateProfileRequest->validated('name');
        $phone = $updateProfileRequest->validated('phone');
        $surname = $updateProfileRequest->validated('surname');
        $patronymic = $updateProfileRequest->validated('patronymic');
        $email = $updateProfileRequest->validated('email');

        if (Auth::user()->phone != $phone) {
            return response()->json(['status' => 'Unauthorized'], 401);
        }

        $userModel = User::where('phone', $phone)->first();
        $userModel->update(['name' => $name]);
        $userModel->update(['surname' => $surname]);
        $userModel->update(['patronymic' => $patronymic]);
        $userModel->update(['email' => $email]);

        UserAdsAgreement::where('phone', $phone)->update(['name' => $name]);

        return response()->json(['status' => 'User updated'], 200);
    }

    public function getCurrentUserData()
    {
        $user = Auth::user();

        if ($user != null) {
            return response()->json([
                'data' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'surname' => $user['surname'],
                    'patronymic' => $user['patronymic'],
                    'email' => $user['email'],
                    'password' => ''
                ]
            ], 200);
        }
    }

    public function getUsers()
    {
        $users = $this->userService->getUsers();
        return UserResource::collection($users);
    }

    public function updateRole(UpdateRoleRequest $request)
    {
        $id = $request->validated('id');
        $role = $request->validated('role');

        $this->userService->updateRole($id, $role);
    }
}