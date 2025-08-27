<?php

namespace App\Http\Controllers\Api\V1\Cache;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use OpenApi\Annotations as OA;

class RewriteCacheController extends Controller
{
    /**
     * @OA\Schema(
     *       schema="Cache/Rewrite",
     *       @OA\Property(
     *           property="status",
     *           type="string"
     *       ),
     *   	@OA\Property(
     *         property="error",
     *         type="string"
     *       )
     *  ),
     *
     * @OA\Get(
     * tags={"Cache"},
     * path="/api/v1/cache-rewrite/",
     * summary="Жесткое обновление кэша",
     * description="Ничего не возвращает",
     * @OA\Response(
     * response=200,
     * description="УСПЕХ!",
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found"
     * )
     * )
     *
     * @return void
     */
    public function index(Request $request): void
    {
        // Note: Жесткое обновление кэша
        Artisan::call('app:update-cache-application-command');
    }
}
