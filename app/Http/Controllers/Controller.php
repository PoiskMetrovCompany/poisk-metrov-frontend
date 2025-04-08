<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Документация по API",
 *     version="1.0.0",
 *     description="API документация",
 * ),
 * @OA\Tag(
 *      name="User",
 *      description="Операции над юзерами для админки"
 *  ),
 * @OA\Tag(
 *       name="UserAccount",
 *       description="Операции над юзерами"
 *   )
 * @OA\Tag(
 *        name="Cbr",
 *        description="Актуализация расписания директоров ЦБ"
 *    )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

}
