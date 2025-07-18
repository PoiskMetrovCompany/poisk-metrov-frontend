<?php

namespace App\Http\Controllers\Api\V1\MaritalStatuses;

use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\MaritalStatuses\MaritalStatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaritalStatusDestroyController extends Controller
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
        $repository = $this->maritalStatusesRepository->destroy($maritalStatuses);

        return new JsonResponse(
            data: [
                'response' => $repository,
                'attributes' => $dataCollection,
            ],
            status: Response::HTTP_OK,
        );
    }
}
