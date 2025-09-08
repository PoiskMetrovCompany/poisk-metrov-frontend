<?php

namespace App\Http\Controllers\Api\V1\Vacancies;

use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vacancies\VacancyUpdateRequest;
use App\Http\Resources\Vacancies\VacancyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class VacancyUpdateController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository
    )
    {

    }

    /**
     * @OA\Post(
     *     tags={"Vacancy"},
     *     path="/api/v1/vacancy/update",
     *     summary="Обновление вакансии",
     *     description="Возвращение JSON объекта",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Данные для обновления вакансии",
     *         @OA\JsonContent(
     *             @OA\Property(property="key", type="string", example="e8ff11fa-822b-11f0-8411-10f60a82b815"),
     *             @OA\Property(property="title", type="string", example="Вакансия"),
     *             @OA\Property(property="description", type="string", example="Описание вакансии"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="УСПЕХ!",
     *     )
     * )
     */
    public function __invoke(VacancyUpdateRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $model = $this->vacancyRepository->findByKey($attributes['key']);
        $repository = $this->vacancyRepository->update($model, $attributes);
        $data = new VacancyResource($repository);

        return new JsonResponse(
            data: $data,
            status: Response::HTTP_CREATED
        );
    }
}


