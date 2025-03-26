<?php

namespace App\Http\Controllers;

use App\Core\Services\ManagersServiceInterface;
use App\Providers\AppServiceProvider;
use App\Services\ManagersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @see AppServiceProvider::registerManagersService()
 */
class ManagersController extends Controller
{
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
