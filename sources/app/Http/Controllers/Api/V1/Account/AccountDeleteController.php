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
    
    /**
     * @OA\Delete(
     *     tags={"Account"},
     *     path="/api/v1/account/delete",
     *     summary="Удаление аккаунта",
     *     description="Возвращение JSON объекта",
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=true,
     *         description="Ключ аккаунта",
     *         @OA\Schema(type="string", example="e8ff11fa-822b-11f0-8411-10f60a82b815")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *     )
     * )
     */
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
