<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountListController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $accounts = $this->account::where(['role' => 'РОП']);
        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => $accounts,
            ],
            status: Response::HTTP_CREATED
        );
    }
}
