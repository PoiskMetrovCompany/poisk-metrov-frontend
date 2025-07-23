<?php

namespace App\Http\Controllers\Api\V1\MaritalStatuses;

use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaritalStatuses\MaritalStatusesUpdateRequest;
use App\Http\Resources\MaritalStatuses\MaritalStatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MaritalStatusUpdateController extends Controller
{
    public function __construct(
        protected MaritalStatusesRepositoryInterface $maritalStatusesRepository
    )
    {

    }

    public function __invoke(MaritalStatusesUpdateRequest $request)
    {
        $attributes = $request->validated();
        $maritalStatuses = $this->maritalStatusesRepository->findByKey($attributes->key);
        $repository = $this->maritalStatusesRepository->update($maritalStatuses, $attributes);
        $dataCollection = new MaritalStatusResource($repository);

        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $dataCollection,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
