<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountUpdateRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountUpdateController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    public function __invoke(AccountUpdateRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $account = $this->account::update($attributes);

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => AccountResource::make($account),
            ],
            status: Response::HTTP_CREATED
        );
    }
}
