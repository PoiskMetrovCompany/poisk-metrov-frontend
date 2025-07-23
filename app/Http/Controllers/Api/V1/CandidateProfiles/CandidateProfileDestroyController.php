<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidateProfileDestroyController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(Request $request)
    {
        $candidateProfile = $this->candidateProfilesRepository->findByKey($request->key);
        $dataCollection = new CandidateProfileResource($candidateProfile);
        $repository = $this->candidateProfilesRepository->destroy($candidateProfile);

        return new JsonResponse(
            data: [
                'response' => $repository,
                'attributes' => $dataCollection,
            ],
            status: Response::HTTP_OK
        );
    }
}
