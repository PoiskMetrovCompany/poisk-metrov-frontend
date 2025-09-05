<?php

namespace App\Http\Controllers\Api\V1\Comparsion;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\ComparisonServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use OpenApi\Annotations as OA;

class ListComparisonController extends AbstractOperations
{
    public function __construct(
        protected ComparisonServiceInterface $comparisonService
    )
    {

    }

    /**
     * @OA\Get(
     *     tags={"Comparsion"},
     *     path="/api/v1/comparison",
     *     summary="Сравнение ЖК и КВ",
     *     description="Возвращение JSON объекта",
     *     @OA\Parameter(
     *         name="user_key",
     *         in="query",
     *         required=true,
     *         description="Ключ юзера",
     *         @OA\Schema(type="string", example="53cbb9a9-4bab-30ce-98f5-ed0277f4ada0")
     *     ),
     *     @OA\Parameter(
     *          name="type_comparison",
     *          in="query",
     *          required=true,
     *          description="Принимает 'Apartments' или 'ResidentialComplexes'",
     *          @OA\Schema(type="string", example="Apartments")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="УСПЕХ!",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $typeComparison = $request->input('type_comparison');
        $userKey = $request->input('user_key');

        if ($typeComparison === 'Apartments') {
            $collect = $this->comparisonService->getComparisonApartments($userKey);
        } elseif ($typeComparison === 'ResidentialComplexes') {
            $collect = $this->comparisonService->getComparisonResidentialComplexes($userKey);
        } else {
            $collect = new Collection();
        }

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return 'Comparison';
    }

    public function getResourceClass(): string
    {
        return 'AbstractComparisonResource';
    }
}
