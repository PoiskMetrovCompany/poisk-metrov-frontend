<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\ManagersServiceInterface;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;

/**
 * @see AppServiceProvider::registerManagersService()
 * @see ManagersServiceInterface
 */
class ManagersController extends Controller
{
    /**
     * @param ManagersServiceInterface $managersService
     */
    public function __construct(protected ManagersServiceInterface $managersService)
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getManagersList(Request $request)
    {
        return $this->managersService->getManagersList();
    }
}
