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

    /**
     * @OA\Get(
     *     tags={"Account"},
     *     path="/api/v1/account/list",
     *     summary="Список аккаунтов",
     *     description="Возвращение JSON объекта",
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *     )
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $accounts = $this->account::where(['role' => 'РОП'])->get();
        return new JsonResponse(
            data: [
                'request' => true,
                'attributes' => $accounts,
            ],
            status: Response::HTTP_OK
        );
    }
}
