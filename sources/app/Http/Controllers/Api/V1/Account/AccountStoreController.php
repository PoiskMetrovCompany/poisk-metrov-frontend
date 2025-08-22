<?php

namespace App\Http\Controllers\Api\V1\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountStoreRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;

class AccountStoreController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    public function __invoke(AccountStoreRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $account = $this->account::create($attributes);

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($account),
            ],
            status: 201
        );
    }
}
