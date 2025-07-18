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
        $account = [];

        $model = $this->account::where(['phone' => $attributes['phone']])->get();
        if ($model->isEmpty()) {
            if ($attributes['code']) $attributes['code'] = Hash::make($attributes['code']);
            if ($attributes['password']) $attributes['password'] = Hash::make($attributes['password']);
            $model->create($attributes);
            $this->index($request);
        }

        if (
            $attributes->code && Hash::check($attributes['code'], $model->code) ||
            $attributes->password && Hash::check($attributes['password'], $model->password)
        ) {
            $account = $this->account::createBearerToken($model);
        }

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($account),
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function setCode(AccountSetCodeRequest $request)
    {
        $attributes = $request->validated();
        $model = $this->account::where(['phone' => $attributes['phone']])->get();
        if ($model) {
            // TODO: генерация и отправка кода
            $code = '000000';
            $model->update(['code' => $code]);
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
