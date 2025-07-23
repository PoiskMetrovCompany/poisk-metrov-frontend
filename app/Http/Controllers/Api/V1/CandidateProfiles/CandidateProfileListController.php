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

/**
 * @OA\Get(
 *       tags={"CandidateProfiles"},
 *       path="/api/v1/candidates/",
 *       summary="получение списка анкет",
 *       description="Возвращение JSON объекта",
 *       security={{"bearerAuth":{}}},
 *       @OA\Response(response=200, description="УСПЕХ!"),
 *       @OA\Response(
 *           response=404,
 *           description="Resource not found"
 *       )
 *  )
 *
 * @param Request $request
 * @return JsonResponse
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

        if ($statuses = $request->input('candidate_statuses')) {
            $statusArray = array_map('trim', explode(',', $statuses));
            $candidateProfiles->whereIn('status', $statusArray);
        }

        if ($vacancyTitles = $request->input('vacancy_title')) {
            $titles = array_map('trim', explode(',', $vacancyTitles));
            $candidateProfiles->whereHas('vacancy', function ($query) use ($titles) {
                $query->whereIn('title', $titles);
            });
        }

        $from = null;
        $to = null;

        if ($request->filled('year_range')) {
            $years = explode(',', $request->input('year_range'));
            $from = $years[0] . '-01-01';
            $to = ($years[1] ?? $years[0]) . '-12-31';
        }
        elseif ($request->filled('month_range')) {
            $dates = explode(',', $request->input('month_range'));
            $start = \DateTime::createFromFormat('m.Y', $dates[0]);
            $end = \DateTime::createFromFormat('m.Y', $dates[1] ?? $dates[0]);

            $from = $start ? $start->format('Y-m-01') : null;
            $to = $end ? $end->format('Y-m-t') : null;
        }
        elseif ($request->filled('date_range')) {
            $dates = explode(',', $request->input('date_range'));
            $start = \DateTime::createFromFormat('d.m.Y', $dates[0]);
            $end = \DateTime::createFromFormat('d.m.Y', $dates[1] ?? $dates[0]);

            $from = $start ? $start->format('Y-m-d') : null;
            $to = $end ? $end->format('Y-m-d') : null;
        }

        if ($from && $to) {
            $candidateProfiles->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
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
