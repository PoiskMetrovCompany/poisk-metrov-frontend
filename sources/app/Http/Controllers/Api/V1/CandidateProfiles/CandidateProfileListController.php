<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateProfiles\CandidateProfileCollection;
use App\Models\CandidateProfiles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Get(
 *     tags={"CandidateProfiles"},
 *     path="/api/v1/candidates/",
 *     summary="Получение списка анкет",
 *     description="Возвращает JSON объект со списком анкет кандидатов с возможностью фильтрации.",
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="city_work",
 *         in="query",
 *         description="Город работы кандидата",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="candidate_statuses",
 *         in="query",
 *         description="Статусы анкет (множественные значения через запятую).",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example=""
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="vacancy_keys",
 *         in="query",
 *         description="UUID вакансий (через запятую)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="year_range",
 *         in="query",
 *         description="Год (например: 2024)",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1900, maximum=2100)
 *     ),
 *     @OA\Parameter(
 *         name="month_range",
 *         in="query",
 *         description="Месяц (01-12)",
 *         required=false,
 *         @OA\Schema(type="integer", minimum=1, maximum=12)
 *     ),
 *     @OA\Parameter(
 *         name="date_range",
 *         in="query",
 *         description="Диапазон дат",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example=""
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
class CandidateProfileListController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $candidateProfiles = CandidateProfiles::query();

        if ($cityWork = $request->input('city_work')) {
            $candidateProfiles->where('city_work', $cityWork);
        }

        if ($statuses = $request->input('candidate_statuses')) {
            $statusArray = array_map('trim', explode(',', $statuses));
            $candidateProfiles->whereIn('status', $statusArray);
        }

        if ($vacancyKeys = $request->input('vacancy_keys')) {
            $vacancyKeysArray = is_array($vacancyKeys) ? $vacancyKeys : explode(',', $vacancyKeys);

            $candidateProfiles->whereHas('vacancy', function ($query) use ($vacancyKeysArray) {
                $query->whereIn('key', $vacancyKeysArray);
            });
        }

        $from = null;
        $to = null;

        if ($request->filled('year_range')) {
            $years = explode(',', $request->input('year_range'));
            if (count($years) >= 1 && is_numeric($years[0])) {
                $from = $years[0] . '-01-01';
                $to = ($years[1] ?? $years[0]) . '-12-31';
            }
        }
        elseif ($request->filled('month_range')) {
            $dates = explode(',', $request->input('month_range'));
            if (count($dates) >= 1) {
                $start = \DateTime::createFromFormat('m.Y', $dates[0]);
                $end = \DateTime::createFromFormat('m.Y', $dates[1] ?? $dates[0]);

                if ($start && $end) {
                    $from = $start->format('Y-m-01');
                    $to = $end->format('Y-m-t');
                }
            }
        }
        elseif ($request->filled('date_range')) {
            $dates = explode(',', $request->input('date_range'));
            if (count($dates) >= 1) {
                $start = \DateTime::createFromFormat('d.m.Y', $dates[0]);
                $end = \DateTime::createFromFormat('d.m.Y', $dates[1] ?? $dates[0]);

                if ($start && $end) {
                    $from = $start->format('Y-m-d');
                    $to = $end->format('Y-m-d');
                }
            }
        }

        if ($from && $to) {
            $candidateProfiles->whereBetween('created_at', [
                $from . ' 00:00:00',
                $to . ' 23:59:59'
            ]);
        }

        $candidateProfiles = $candidateProfiles
            ->latest()
            ->paginate($request->get('per_page', 8));

        $dataCollection = new CandidateProfileCollection($candidateProfiles);

        return new JsonResponse([
            'response' => true,
            'attributes' => $dataCollection->response()->getData(true),
        ], Response::HTTP_OK);
    }
}
