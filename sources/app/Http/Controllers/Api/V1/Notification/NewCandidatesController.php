<?php

namespace App\Http\Controllers\Api\V1\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class NewCandidatesController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Notification"},
     *     path="/api/v1/notification/new-candidates",
     *     summary="Получения лога анкет кандидатов по ключу",
     *     description="Возвращение JSON объекта",
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Иван Иванов"),
     *             @OA\Property(property="email", type="string", example="ivan@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Анкета не найдена")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $attributes = DB::connection('pm-log')
            ->table('candidate_profiles_has')
            ->select()
            ->where('is_visible', '=', false)
            ->limit(10)
            ->get();
            
        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $attributes
            ],
            status: 200
        );
    }
}
