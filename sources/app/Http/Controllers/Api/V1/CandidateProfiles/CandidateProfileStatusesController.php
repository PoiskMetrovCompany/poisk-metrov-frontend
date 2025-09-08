<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Common\VacancyStatusesEnum;
use App\Http\Controllers\Api\V1\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"CandidateProfiles"},
 *     path="/api/v1/candidates/get-statuses",
 *     summary="Получение статусов для анкеты кандидата",
 *     description="Возвращение JSON объекта",
 *     @OA\Response(
 *         response=200,
 *         description="УСПЕХ!",
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
class CandidateProfileStatusesController extends Controller
{
    public function __invoke(Request $request)
    {
        return new JsonResponse(
            data: [
                VacancyStatusesEnum::New->value,
                VacancyStatusesEnum::Verified->value,
                VacancyStatusesEnum::Rejected->value,
                VacancyStatusesEnum::NeedsImprovement->value,
                VacancyStatusesEnum::Accepted->value,
                VacancyStatusesEnum::NotAccepted->value,
                VacancyStatusesEnum::CameOut->value,
                VacancyStatusesEnum::NotCameOut->value,
            ],
            status: Response::HTTP_CREATED
        );
    }
}
