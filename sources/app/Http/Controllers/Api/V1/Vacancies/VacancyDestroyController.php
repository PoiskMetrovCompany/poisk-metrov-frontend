<?php

namespace App\Http\Controllers\Api\V1\Vacancies;

use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vacancies\VacancyCollection;
use App\Http\Resources\Vacancies\VacancyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Delete(
 *     tags={"Vacancy"},
 *     path="/api/v1/vacancy/destroy",
 *     summary="Удаление вакансии",
 *     description="Возвращение JSON объекта",
 *     @OA\Parameter(
 *         name="key",
 *         in="query",
 *         required=true,
 *         description="Ключ вакансии",
 *         @OA\Schema(type="string", example="e8ff11fa-822b-11f0-8411-10f60a82b815")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="УСПЕХ!",
 *     )
 * )
 */
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
