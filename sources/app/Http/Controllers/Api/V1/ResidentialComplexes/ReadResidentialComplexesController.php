<?php

namespace App\Http\Controllers\Api\V1\ResidentialComplexes;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResidentialComplexes\ResidentialComplexesResource;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @see ResidentialComplexRepositoryInterface
 */
class ReadResidentialComplexesController extends AbstractOperations
{
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
    )
    {

    }

    /**
     * @OA\Get(
     *      tags={"ResidentialComplex"},
     *      path="/api/v1/residential-complex/read",
     *      summary="получение конкретного ЖК",
     *      description="Возвращение JSON объекта",
     *      @OA\Parameter(
     *          name="key",
     *          in="query",
     *          required=true,
     *          description="Ключ для получения ЖК",
     *          @OA\Schema(type="string", example="")
     *      ),
     *      @OA\Parameter(
     *          name="includes",
     *          in="query",
     *          description="Указывает, какие связанные данные нужно включить в выборку",
     *          required=false,
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="string",
     *                  enum={
     *                       "Apartment",
     *                       "Building"
     *                   }
     *              )
     *          )
     *      ),
     *      @OA\Parameter(
     *           name="filter",
     *           in="query",
     *           description="фильтрация сущности указанной в includes",
     *           required=false,
     *           style="form",
     *           explode=true,
     *           @OA\Schema(
     *               type="array",
     *               @OA\Items(
     *                   type="string",
     *                   enum={
     *                        "apartments.room",
     *                    }
     *               )
     *           )
     *      ),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $key = $request->input('key');
        $attributes = $this->residentialComplexRepository->findByKey($key);
        $collect = new ResidentialComplexesResource($attributes);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK,
        );
    }

    public function getEntityClass(): string
    {
        return ResidentialComplex::class;
    }

    public function getResourceClass(): string
    {
        return ResidentialComplexesResource::class;
    }
}
