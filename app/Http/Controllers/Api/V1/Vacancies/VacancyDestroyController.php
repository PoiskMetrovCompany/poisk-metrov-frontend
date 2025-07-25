<?php

namespace App\Http\Controllers\Api\V1\Vacancies;

use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vacancies\VacancyCollection;
use App\Http\Resources\Vacancies\VacancyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VacancyDestroyController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $vacancy = $this->vacancyRepository->findByKey($request->key);
        $repository = $this->vacancyRepository->destroy($vacancy);
        $data = new VacancyResource($repository);

        return new JsonResponse(
            data: [],
            status: Response::HTTP_OK
        );
    }
}
