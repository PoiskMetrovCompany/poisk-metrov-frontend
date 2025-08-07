<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * TODO: добавить схемы
 *
 * @OA\Info(
 *     title="Документация по API",
 *     version="1.0.0",
 *     description="API документация",
 * ),
 * @OA\SecurityScheme(
 *      type="http",
 *      securityScheme="bearerAuth",
 *      scheme="bearer"
 * )
 * @OA\Tag(
 *      name="User",
 *      description="Операции над юзерами для админки"
 *  ),
 * @OA\Tag(
 *      name="UserAccount",
 *      description="Операции над юзерами"
 * )
 * @OA\Tag(
 *      name="Cbr",
 *      description="Актуализация расписания директоров ЦБ"
 * )
 * @OA\Tag(
 *      name="RealEstate",
 *      description="Операции над планировками"
 * )
 * @OA\Tag(
 *      name="Apartment",
 *      description="Операции над квартирами"
 *  )
 *
 * @OA\Tag(
 *      name="Manager",
 *      description="Операции над менеджерами"
 * )
 *
 * @OA\Tag(
 *      name="Feed",
 *      description="Операции над фидами"
 *  )
 *
 * @OA\Tag(
 *      name="Favorite",
 *      description="Операции над избранным"
 * )
 *
 * @OA\Tag(
 *      name="ResidentialComplex",
 *      description="Операции над ЖК"
 * )
 *
 * @OA\Tag(
 *      name="Chat",
 *      description="Операции над чатом с клиентом + синх с ЦРМ"
 * )
 *
 * @OA\Tag(
 *      name="Visited",
 *      description="Операции над посещениями"
 * )
 *
 * @OA\Tag(
 *       name="Сrm",
 *       description="Операции связанные с Энви ЦРМ"
 *  )
 *
 * @OA\SecurityScheme(
 *        type="http",
 *        securityScheme="bearerAuth",
 *        scheme="bearer"
 *  ),
 *
 * @OA\Tag(
 *       name="Account",
 *       description="Операции над юзерами для аккаунтами кандидата/безопасника"
 * ),
 *
 * @OA\Tag(
 *        name="Vacancy",
 *        description="Операции над вакансиями"
 * ),
 *
 * @OA\Tag(
 *         name="MaritalStatus",
 *         description="Операции над семейным положением"
 * ),
 *
 * @OA\Tag(
 *          name="CandidateProfiles",
 *          description="Операции над анкетами кандидатов"
 * ),
 *
 * @OA\Tag(
 *           name="Export",
 *           description="Операции экспорта"
 * ),
 *
 * @OA\Tag(
 *            name="Notification",
 *            description="Операции над уведомлениями"
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

}
