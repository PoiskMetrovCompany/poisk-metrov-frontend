<?php

namespace App\Http\Controllers\Api\V1\UserFilters;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFilters\StoreUserFilterRequest;
use App\Http\Resources\UserFilter\UserFilterResource;
use App\Models\UserFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class StoreUserFilterController extends Controller
{
    /**
     * @OA\Post(
     *       tags={"User Filters"},
     *       path="/api/v1/user-filters/store",
     *       summary="Сохранить фильтр пользователя",
     *       description="Сохранение фильтра пользователя для поиска недвижимости. ВАЖНО: Параметр user_key должен быть передан в URL как query parameter. В Swagger UI необходимо вручную добавить ?user_key=ВАШ_UUID к URL в поле запроса. Пример: /api/v1/user-filters/store?user_key=06cf3c62-83c2-11f0-a013-10f60a82b815",
     *       @OA\Parameter(
     *           name="user_key",
     *           in="query",
     *           required=true,
     *           description="UUID ключ пользователя",
     *           @OA\Schema(type="string", format="uuid", example="06cf3c62-83c2-11f0-a013-10f60a82b815")
     *       ),
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="type", type="string", example="apartment", description="Тип недвижимости"),
     *              @OA\Property(property="rooms", type="integer", example=2, description="Количество комнат"),
     *              @OA\Property(property="price", type="string", example="5000000-10000000", description="Диапазон цен"),
     *              @OA\Property(property="floors", type="string", example="1-5", description="Диапазон этажей"),
     *              @OA\Property(property="area_full", type="string", example="50-80", description="Полная площадь"),
     *              @OA\Property(property="area_living", type="string", example="30-60", description="Жилая площадь"),
     *              @OA\Property(property="area_plot", type="string", example="6-10", description="Площадь участка"),
     *              @OA\Property(property="ceiling_height", type="string", example="2.7-3.0", description="Высота потолков"),
     *              @OA\Property(property="house_type", type="string", example="panel", description="Тип дома"),
     *              @OA\Property(property="finishing", type="string", example="with", description="Отделка"),
     *              @OA\Property(property="bathroom", type="string", example="combined", description="Санузел"),
     *              @OA\Property(property="features", type="string", example="balcony", description="Особенности"),
     *              @OA\Property(property="security", type="string", example="concierge", description="Охрана"),
     *              @OA\Property(property="water_supply", type="string", example="central", description="Водоснабжение"),
     *              @OA\Property(property="electricity", type="string", example="central", description="Электричество"),
     *              @OA\Property(property="sewerage", type="string", example="central", description="Канализация"),
     *              @OA\Property(property="heating", type="string", example="central", description="Отопление"),
     *              @OA\Property(property="gasification", type="string", example="central", description="Газоснабжение"),
     *              @OA\Property(property="to_metro", type="string", example="5-15", description="До метро (мин)"),
     *              @OA\Property(property="to_center", type="string", example="10-30", description="До центра (мин)"),
     *              @OA\Property(property="to_busstop", type="string", example="1-5", description="До остановки (мин)"),
     *              @OA\Property(property="to_train", type="string", example="5-20", description="До вокзала (мин)"),
     *              @OA\Property(property="near", type="string", example="park,school", description="Рядом с"),
     *              @OA\Property(property="garden_community", type="boolean", example=true, description="Загородный поселок"),
     *              @OA\Property(property="in_city", type="boolean", example=false, description="В черте города"),
     *              @OA\Property(property="payment_method", type="string", example="cash", description="Способ оплаты"),
     *              @OA\Property(property="mortgage", type="string", example="available", description="Ипотека"),
     *              @OA\Property(property="installment_plan", type="string", example="available", description="Рассрочка"),
     *              @OA\Property(property="down_payment", type="string", example="10-20", description="Первоначальный взнос (%)"),
     *              @OA\Property(property="mortgage_programs", type="string", example="family", description="Ипотечные программы")
     *          )
     *        ),
     *       @OA\Response(
     *           response=200,
     *           description="Фильтр успешно сохранен",
     *           @OA\JsonContent(
     *               @OA\Property(property="identifier", type="string", example="uuid"),
     *               @OA\Property(property="attributes", type="object",
     *                   @OA\Property(property="id", type="integer", example=1),
     *                   @OA\Property(property="type", type="string", example="apartment"),
     *                   @OA\Property(property="rooms", type="integer", example=2),
     *                   @OA\Property(property="user_key", type="string", example="06cf3c62-83c2-11f0-a013-10f60a82b815")
     *               ),
     *               @OA\Property(property="meta", type="object",
     *                   @OA\Property(property="copyright", type="string", example="ПОИСК МЕТРОВ © 2025")
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Ошибка валидации",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="The given data was invalid."),
     *               @OA\Property(property="errors", type="object",
     *                   @OA\Property(property="user_key", type="array", @OA\Items(type="string"))
     *               )
     *           )
     *       )
     *  )
     *
     * @param StoreUserFilterRequest $request
     * @return JsonResponse
     */
    public function __invoke(StoreUserFilterRequest $request): JsonResponse
    {
        $filterData = $request->validated();
        $filterData['user_key'] = $request->query('user_key');

        $filter = UserFilter::create($filterData);

        return new JsonResponse([
            'identifier' => Str::uuid()->toString(),
            'attributes' => $filter->toArray(),
            'meta' => [
                'copyright' => 'ПОИСК МЕТРОВ © 2025',
                'request' => [
                    'identifier' => Str::uuid()->toString(),
                    'method' => $request->method(),
                    'path' => $request->decodedPath(),
                    'attributes' => $filterData,
                    'timestamp' => date(DATE_RFC2822),
                ]
            ]
        ]);
    }
}
