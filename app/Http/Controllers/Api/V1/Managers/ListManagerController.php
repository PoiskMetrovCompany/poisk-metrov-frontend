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
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
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
        $managers = $this->managersService->getManagersList();

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
