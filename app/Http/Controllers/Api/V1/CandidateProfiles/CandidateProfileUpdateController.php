<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateProfiles\CandidateProfilesUpdateRequest;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CandidateProfileUpdateController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(CandidateProfilesUpdateRequest $request)
    {
        $attributes = $request->validated();
        $candidateProfile =  $this->candidateProfilesRepository->findByKey($attributes['key']);
        $repository = $this->candidateProfilesRepository->update($candidateProfile, $attributes);
        $dataCollection = new CandidateProfileResource($repository);

        return new JsonResponse(
            data: $dataCollection,
            status: Response::HTTP_CREATED
        );
    }
}
