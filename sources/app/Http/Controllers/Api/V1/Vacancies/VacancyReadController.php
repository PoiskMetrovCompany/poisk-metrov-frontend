<?php

namespace App\Http\Controllers\Api\V1\Vacancies;

use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vacancies\VacancyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VacancyReadController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    )
    {

    }

    /**
    * @OA\Get(
    *     tags={"Vacancy"},
    *     path="/api/v1/vacancy/read",
    *     summary="Получение вакансии",
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
    public function __invoke(Request $request): JsonResponse
    {
        $repository = $this->vacancyRepository->findByKey($request->key);
        $data = new VacancyResource($repository);

        return new JsonResponse(
            data: $data,
            status: Response::HTTP_OK
        );
    }
}
