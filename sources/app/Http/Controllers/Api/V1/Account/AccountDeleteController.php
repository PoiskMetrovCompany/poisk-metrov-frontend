<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountDeleteController extends Controller
{
    public function __construct(
        protected Account $account,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $account =  $this->account::where(['key' => $request->input('key')])->first();
        $account->delete();

        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => $account,
            ],
            status: Response::HTTP_OK
        );
    }
}
