<?php

namespace App\Http\Controllers\Api\V1\MaritalStatuses;

use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaritalStatuses\MaritalStatusesStoreRequest;
use App\Http\Resources\MaritalStatuses\MaritalStatusCollection;
use App\Http\Resources\MaritalStatuses\MaritalStatusResource;
use App\Http\Resources\Vacancies\VacancyCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *       tags={"MaritalStatus"},
 *       path="/api/v1/marital-statuses/",
 *       summary="получение списка семейного положения",
 *       description="Возвращение JSON объекта",
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
class MaritalStatusListController extends Controller
{

    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $repository = $this->vacancyRepository->list([]);

        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $repository
            ],
            status: Response::HTTP_OK
        );
    }
}
