<?php

namespace App\Http\Controllers\Api\V1\Vacancies;

use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vacancies\VacancyCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VacancyListController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $repository = $this->vacancyRepository->list([]);
        $dataCollection = new VacancyCollection($repository);

        return new JsonResponse(
            data: $dataCollection->response,
            status: Response::HTTP_OK
        );
    }
}
