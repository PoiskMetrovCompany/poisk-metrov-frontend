<?php

namespace App\Http\Controllers;

use App\Services\ManagersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagersController extends Controller
{
    public function __construct(protected ManagersService $managersService)
    {
    }

    public function getManagersList(Request $request)
    {
        return $this->managersService->getManagersList();
    }
}