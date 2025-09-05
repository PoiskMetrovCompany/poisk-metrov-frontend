<?php

namespace App\Http\Controllers\Api\V1\MaritalStatuses;

use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Abstracts\AbstractOperations;
use App\Http\Requests\MaritalStatuses\MaritalStatusesStoreRequest;
use App\Http\Resources\MaritalStatuses\MaritalStatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaritalStatusReadController extends AbstractOperations
{
    public function __construct(
        protected MaritalStatusesRepositoryInterface $maritalStatusesRepository
    )
    {

    }

    public function __invoke(Request $request)
    {
        $maritalStatuses = $this->maritalStatusesRepository->findByKey($request->key);
        $dataCollection = new MaritalStatusResource($maritalStatuses);

        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $dataCollection,
            ],
            status: Response::HTTP_OK,
        );
    }
    public function getEntityClass(): string
    {
        return MaritalStatuses::class;
    }

    public function getResourceClass(): string
    {
        return MaritalStatusResource::class;
    }
}
