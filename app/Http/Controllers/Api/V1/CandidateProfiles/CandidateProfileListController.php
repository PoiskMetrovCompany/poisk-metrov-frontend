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

    public function __invoke(Request $request)
    {
        $candidateProfile = CandidateProfiles::all()->reverse();
        $dataCollection = new CandidateProfileCollection($candidateProfile);
        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $dataCollection->resource,
            ],
            status: Response::HTTP_OK
        );
    }
}
