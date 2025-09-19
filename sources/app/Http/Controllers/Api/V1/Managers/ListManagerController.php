<?php

namespace App\Http\Controllers\Api\V1\Managers;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\ManagersServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Managers\ManagerResource;
use App\Models\Manager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class ListManagerController extends AbstractOperations
{
    /**
     * @param ManagersServiceInterface $managersService
     */
    public function __construct(protected ManagersServiceInterface $managersService)
    {
    }

    /**
     * @OA\Get(
     *       tags={"Manager"},
     *       path="/api/v1/managers/list",
     *       summary="получение списка менеджеров",
     *       description="Возвращение JSON объекта с возможностью включения связанных данных через параметр includes",
     *       security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *           name="includes",
     *           in="query",
     *           description="Указывает, какие связанные данные нужно включить",
     *           required=false,
     *           style="form",
     *           explode=true,
     *           @OA\Schema(
     *               type="array",
     *               @OA\Items(
     *                   type="string",
     *                   enum={"city"}
     *               )
     *           )
     *       ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        // Получаем параметр includes
        $includes = $request->get('includes', []);
        
        // Загружаем менеджеров с условной загрузкой связанных данных
        $query = Manager::query();
        
        // Если в includes указан 'city', загружаем связанный город
        if (in_array('city', $includes)) {
            $query->with('city');
        }
        
        $managers = $query->get();

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($managers),
                ...self::metaData($request, $managers->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return Manager::class;
    }

    public function getResourceClass(): string
    {
        return ManagerResource::class;
    }
}
