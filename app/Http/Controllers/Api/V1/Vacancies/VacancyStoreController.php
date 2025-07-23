<?php

namespace App\Http\Controllers\Api\V1\Vacancies;

use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vacancies\VacancyStoreRequest;
use App\Http\Resources\Vacancies\VacancyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class VacancyStoreController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    )
    {

    }

    public function __invoke(VacancyStoreRequest $request): JsonResponse
    {
        $vacancy = $request->validated();
        $repository = $this->vacancyRepository->store($vacancy);
        $data = new VacancyResource($repository);

        return new JsonResponse(
            data: $data,
            status: Response::HTTP_CREATED
        );
    }
}
