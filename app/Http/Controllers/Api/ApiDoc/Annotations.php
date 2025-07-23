<?php

namespace App\Http\Controllers\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Документация по API",
 *     version="1.0.0",
 *     description="API документация",
 * ),
 *
 * @OA\SecurityScheme(
 *       type="http",
 *       securityScheme="bearerAuth",
 *       scheme="bearer"
 * ),
 *
 * @OA\Tag(
 *      name="Account",
 *      description="Операции над юзерами для аккаунтами кандидата/безопасника"
 * ),
 *
 * @OA\Tag(
 *       name="Vacancy",
 *       description="Операции над вакансиями"
 * ),
 *
 * @OA\Tag(
 *        name="MaritalStatus",
 *        description="Операции над семейным положением"
 * ),
 *
 * @OA\Tag(
 *         name="CandidateProfiles",
 *         description="Операции над анкетами кандидатов"
 * ),
 *
 * @OA\Tag(
 *          name="Export",
 *          description="Операции экспорта"
 *  ),
 *
 * @OA\Tag(
 *           name="Notification",
 *           description="Операции над уведомлениями"
 *   ),
 */
class Annotations
{

}
