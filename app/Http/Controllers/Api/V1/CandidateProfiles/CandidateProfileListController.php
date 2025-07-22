<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateProfiles\CandidateProfileCollection;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use App\Models\CandidateProfiles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidateProfileListController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $candidateProfiles = CandidateProfiles::query()
            ->latest()
            ->paginate($request->get('per_page', 8));

        $dataCollection = new CandidateProfileCollection($candidateProfiles);

        return new JsonResponse([
            'response' => true,
            'attributes' => $dataCollection->response()->getData(true),
        ], Response::HTTP_OK);
    }
}
