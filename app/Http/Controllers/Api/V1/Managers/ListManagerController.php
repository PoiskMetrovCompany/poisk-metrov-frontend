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

class ListManagerController extends AbstractOperations
{
    /**
     * @param ManagersServiceInterface $managersService
     */
    public function __construct(protected ManagersServiceInterface $managersService)
    {
    }

    /**
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
