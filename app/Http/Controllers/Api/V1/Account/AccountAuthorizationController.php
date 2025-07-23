<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountLoginRequest;
use App\Http\Requests\Accounts\AccountSetCodeRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AccountAuthorizationController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    public function index(AccountLoginRequest $request)
    {
        $attributes = $request->validated();
        $model = $this->account::where(['phone' => $attributes['phone']])->first();

        if (
            (key_exists('phone', $attributes) && key_exists('code', $attributes) && Hash::check($attributes['code'], $model->secret)) ||
            (key_exists('email', $attributes) && key_exists('password', $attributes) && Hash::check($attributes['password'], $model->secret))
        ) {
            $account = $this->account::createBearerToken($model);
        }

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => [
                    'user' => AccountResource::make($model),
                    'access_token' => $account,
                ],
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function setCode(AccountSetCodeRequest $request)
    {
        $attributes = $request->validated();
        if (empty($this->account::where(['phone' => $attributes['phone']])->first())) {
            $attributes['key'] = Str::uuid()->toString();
            $this->account::create($attributes);
            return $this->setCode($request);
        } else {
            // TODO: генерация и отправка кода
            $code = '000000';
            $model = $this->account::where(['phone' => $attributes['phone']])->first();
            $model->update(['secret' => Hash::make($code)]);
        }
        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($model),
            ],
            status:  Response::HTTP_OK
        );
    }
}
