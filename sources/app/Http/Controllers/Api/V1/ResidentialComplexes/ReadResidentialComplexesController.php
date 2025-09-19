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
     *           description="фильтрация сущности указанной в includes. apartments.filters - возвращает агрегированные данные фильтров (этажи, площади, отделка)",
     *           required=false,
     *           style="form",
     *           explode=true,
     *           @OA\Schema(
     *               type="array",
     *               @OA\Items(
     *                   type="string",
     *                   enum={
     *                        "apartments.room",
     *                        "apartments.filters"
     *                    }
     *               )
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="УСПЕХ! Возвращает данные ЖК. С фильтром apartments.filters возвращает агрегированные данные фильтров.",
     *          @OA\JsonContent(
     *              oneOf={
     *                  @OA\Schema(
     *                      @OA\Property(property="identifier", type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="type", type="string", example="residential_complex")
     *                      ),
     *                      @OA\Property(property="attributes", type="object",
     *                          @OA\Property(property="id", type="integer", example=1),
     *                          @OA\Property(property="key", type="string", example="06d561aa-83c2-11f0-a013-10f60a82b815"),
     *                          @OA\Property(property="name", type="string", example="Эверест"),
     *                          @OA\Property(property="includes", type="array", @OA\Items(type="object"))
     *                      ),
     *                      @OA\Property(property="meta", type="object")
     *                  ),
     *                  @OA\Schema(
     *                      @OA\Property(property="identifier", type="object"),
     *                      @OA\Property(property="attributes", type="object"),
     *                      @OA\Property(property="includes", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="type", type="string", example="filters"),
     *                              @OA\Property(property="attributes", type="object",
     *                                  @OA\Property(property="floors", type="object",
     *                                      @OA\Property(property="list", type="array", @OA\Items(type="integer"), example={1,2,3,4,5}),
     *                                      @OA\Property(property="count", type="integer", example=5)
     *                                  ),
     *                                  @OA\Property(property="apartment_area", type="object",
     *                                      @OA\Property(property="min", type="number", format="float", example=25.5),
     *                                      @OA\Property(property="max", type="number", format="float", example=120.0)
     *                                  ),
     *                                  @OA\Property(property="kitchen_area", type="object",
     *                                      @OA\Property(property="min", type="number", format="float", example=8.5),
     *                                      @OA\Property(property="max", type="number", format="float", example=25.0)
     *                                  ),
     *                                  @OA\Property(property="finishing", type="object",
     *                                      @OA\Property(property="list", type="array", @OA\Items(type="string"), example={"Черновая","Под чистовую","Чистовая"}),
     *                                      @OA\Property(property="count", type="integer", example=3)
     *                                  )
     *                                  ),
     *                                  @OAProperty(property=price, type=object,
     *                                      @OAProperty(property=min, type=number, format=float, example=3500000),
     *                                      @OAProperty(property=max, type=number, format=float, example=8500000)
     *                                  )
     *                              )
     *                          )
     *                      ),
     *                      @OA\Property(property="meta", type="object")
     *                  )
     *              }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="identifier", type="object"),
     *              @OA\Property(property="error", type="string", example="Residential complex not found"),
     *              @OA\Property(property="message", type="string", example="ЖК с ключом 'invalid-key' не найден")
     *          )
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $key = $request->input('key');
        $attributes = $this->residentialComplexRepository->findByKey($key);
        

        if (!$attributes) {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    'error' => 'Residential complex not found',
                    'message' => "ЖК с ключом '{$key}' не найден"
                ],
                status: Response::HTTP_NOT_FOUND,
            );
        }
        
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
