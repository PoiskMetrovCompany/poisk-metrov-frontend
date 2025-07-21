<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateProfiles\CandidateProfilesStoreRequest;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CandidateProfileStoreController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(CandidateProfilesStoreRequest $request)
    {
        $attributes = $request->validated();
        $attributes['key'] = Str::uuid()->toString();
        $candidateProfile = $this->candidateProfilesRepository->store($attributes);
        $dataCollection = new CandidateProfileResource($candidateProfile);

        return new JsonResponse(
            data: $dataCollection,
            status: Response::HTTP_CREATED
        );
    }
}
