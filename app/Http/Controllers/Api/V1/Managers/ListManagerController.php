<?php

namespace App\Http\Controllers\Api\V1\Managers;

use App\Core\Interfaces\Services\ManagersServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListManagerController extends Controller
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
    public function getManagersList(Request $request)
    {
        return new JsonResponse(
            data: $this->managersService->getManagersList(),
            status: Response::HTTP_OK
        );
    }
}
