<?php

namespace App\Http\Controllers\Api\V1\Crm;

use App\Core\Abstracts\AbstractOperations;
use App\CRM\Commands\CreateLead;
use App\Http\Resources\Crm\CrmResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

class StoreClientTransferController extends AbstractOperations
{
    /**
     * @OA\Post(
     *       tags={"Crm"},
     *       path="/api/v1/crm/client-transfer",
     *       summary="Отправка заявки на передачу клиента в ЦРМ",
     *       description="Возвращение JSON объекта",
     *
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="last_name", type="string", example=""),
     *              @OA\Property(property="first_name", type="string", example=""),
     *              @OA\Property(property="middle_name", type="string", example=""),
     *              @OA\Property(property="phone", type="string", example=""),
     *              @OA\Property(property="client_last_name", type="string", example=""),
     *              @OA\Property(property="client_first_name", type="string", example=""),
     *              @OA\Property(property="client_middle_name", type="string", example=""),
     *              @OA\Property(property="client_phone", type="string", example=""),
     *              @OA\Property(property="city", type="string", example=""),
     *          )
     *        ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'phone' => 'required',
            'client_last_name' => 'required',
            'client_first_name' => 'required',
            'client_middle_name' => 'required',
            'client_phone' => 'required',
            'city' => 'required'
        ]);

        $comment = 'Передача клиента<br/>';
        $comment .= "Город в котором планируется покупка - {$validated['city']}<br/>";
        $comment .= 'Агент:<br/>';
        $comment .= "{$validated['first_name']} | {$validated['last_name']} | {$validated['middle_name']} | {$validated['phone']}<br/>";
        $comment .= 'Клиент: <br/>';
        $comment .= "{$validated['client_first_name']} | {$validated['client_last_name']} | {$validated['client_middle_name']} | {$validated['client_phone']}";

        $createLead = new CreateLead("САЙТ!", $validated['phone'], $comment, $validated['city']);
        $result = $createLead->execute();
        $result = json_decode($result);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($result),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function getEntityClass(): string
    {
        return 'Crm';
    }

    public function getResourceClass(): string
    {
        return CrmResource::class;
    }
}
