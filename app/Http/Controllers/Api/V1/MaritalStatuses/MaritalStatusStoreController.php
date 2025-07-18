<?php

namespace App\Http\Controllers\Api\V1\MaritalStatuses;

use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaritalStatuses\MaritalStatusesStoreRequest;
use App\Http\Resources\MaritalStatuses\MaritalStatusCollection;
use App\Http\Resources\MaritalStatuses\MaritalStatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaritalStatusStoreController extends Controller
{
    public function __construct(
        protected MaritalStatusesRepositoryInterface $maritalStatusesRepository
    )
    {

    }

    public function __invoke(MaritalStatusesStoreRequest $request)
    {
        $attributes = $request->validated();
        $maritalStatuses = $this->maritalStatusesRepository->store($attributes);
        $dataCollection = new MaritalStatusResource($maritalStatuses);

        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $dataCollection,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
