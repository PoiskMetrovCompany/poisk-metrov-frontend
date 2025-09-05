<?php

namespace App\Http\Controllers\Api\V1\CatalogueStatistic;

/**
 * @OA\Get(
 *     path="/api/v1/catalogue-statistic",
 *     tags={"CatalogueStatistic"},
 *     summary="Получить статистику каталога по городам",
 *     description="Возвращает статистику ЖК и квартир для указанного города или для всех городов",
 *     operationId="getCatalogueStatistic",
 *     @OA\Parameter(
 *         name="city_code",
 *         in="query",
 *         description="Код города для фильтрации статистики (например: novosibirsk, moscow)",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="novosibirsk"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Успешный ответ со статистикой каталога",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="identifier", type="string", format="uuid", description="Уникальный идентификатор запроса"),
 *             @OA\Property(
 *                 property="attributes",
 *                 type="array",
 *                 description="Массив статистики по типам объектов",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="type", type="string", enum={"ЖК", "Квартиры"}, description="Тип объекта"),
 *                     @OA\Property(
 *                         property="meta",
 *                         type="array",
 *                         description="Метаданные со счетчиками",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="title", type="string", description="Название категории"),
 *                             @OA\Property(property="count", type="integer", description="Количество объектов")
 *                         )
 *                     )
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(property="copyright", type="string", description="Копирайт"),
 *                 @OA\Property(
 *                     property="request",
 *                     type="object",
 *                     @OA\Property(property="identifier", type="string", format="uuid"),
 *                     @OA\Property(property="method", type="string"),
 *                     @OA\Property(property="path", type="string"),
 *                     @OA\Property(property="attributes", type="object"),
 *                     @OA\Property(property="timestamp", type="string", format="date-time")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Внутренняя ошибка сервера"
 *     )
 * )
 */

use App\Core\Abstracts\AbstractOperations;
use App\Core\Common\CatalogueStatisticTemplate;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\CatalogueStatisticServiceInterface;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Http\Resources\CatalogueStatistic\CatalogueStatisticResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use OpenApi\Annotations as OA;

class ListCatalogueStatisticController extends AbstractOperations
{
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface          $apartmentRepository,
        protected CatalogueStatisticTemplate            $catalogueStatisticTemplate,
        protected CatalogueStatisticServiceInterface    $catalogueStatisticService,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $cityCode = $request->get('city_code');
        $collect = $this->catalogueStatisticService->getCatalogueStatistics($cityCode);

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
        return 'AbstractCatalogueStatisticResource';
    }

    public function getResourceClass(): string
    {
        return CatalogueStatisticResource::class;
    }
}
