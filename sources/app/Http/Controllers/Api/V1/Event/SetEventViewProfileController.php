<?php

namespace App\Http\Controllers\Api\V1\Event;

use App\Http\Controllers\Controller;
use App\Jobs\ReadEnentlistnerViewProfileQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Event"},
 *     path="/api/v1/event/view-profile",
 *     summary="Запись в лог анкеты кандидата по ключу",
 *     description="Возвращение JSON объекта",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="profile_key",
 *         in="query",
 *         required=true,
 *         description="Ключ для записи",
 *         @OA\Schema(type="string", example="53cbb9a9-4bab-30ce-98f5-ed0277f4ada0")
 *     ),
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
class SetEventViewProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->input('profile_key')) {
            ReadEnentlistnerViewProfileQueue::dispatch($request->input('profile_key'));
        }
        return new JsonResponse(
            data: [
                'response' => true,
            ],
            status: Response::HTTP_OK
        );
    }
}
