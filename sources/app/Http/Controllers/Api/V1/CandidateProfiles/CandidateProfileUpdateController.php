<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateProfiles\CandidateProfilesStoreRequest;
use App\Http\Requests\CandidateProfiles\CandidateProfilesUpdateRequest;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"CandidateProfiles"},
 *     path="/api/v1/candidates/update",
 *     summary="Обновление анкеты кандидата.",
 *     description="Возвращение JSON объекта",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные для обновления анкеты кандидата",
 *         @OA\JsonContent(
 *             @OA\Property(property="key", type="string", example=""),
 *             @OA\Property(property="vacancies_key", type="string", example=""),
 *             @OA\Property(property="marital_statuses_key", type="string", example=""),
 *             @OA\Property(property="status", type="string", example=""),
 *             @OA\Property(property="first_name", type="string", example=""),
 *             @OA\Property(property="last_name", type="string", example=""),
 *             @OA\Property(property="middle_name", type="string", example=""),
 *             @OA\Property(property="reason_for_changing_surnames", type="string", example=""),
 *             @OA\Property(property="birth_date", type="string", example=""),
 *             @OA\Property(property="country_birth", type="string", example=""),
 *             @OA\Property(property="city_birth", type="string", example=""),
 *             @OA\Property(property="level_educational", type="string", example=""),
 *             @OA\Property(property="courses", type="string", example=""),
 *             @OA\Property(property="educational_institution", type="string", example=""),
 *             @OA\Property(property="organization_name", type="string", example=""),
 *             @OA\Property(property="organization_phone", type="string", example=""),
 *             @OA\Property(property="field_of_activity", type="string", example=""),
 *             @OA\Property(property="organization_address", type="string", example=""),
 *             @OA\Property(property="organization_job_title", type="string", example=""),
 *             @OA\Property(property="organization_price", type="string", example=""),
 *             @OA\Property(property="date_of_hiring", type="string", example=""),
 *             @OA\Property(property="date_of_dismissal", type="string", example=""),
 *             @OA\Property(property="reason_for_dismissal", type="string", example=""),
 *             @OA\Property(property="recommendation_contact", type="string", example=""),
 *             @OA\Property(property="mobile_phone_candidate", type="string", example=""),
 *             @OA\Property(property="home_phone_candidate", type="string", example=""),
 *             @OA\Property(property="mail_candidate", type="string", example=""),
 *             @OA\Property(property="inn", type="string", example=""),
 *             @OA\Property(property="passport_series", type="string", example=""),
 *             @OA\Property(property="passport_number", type="string", example=""),
 *             @OA\Property(property="passport_issued", type="string", example=""),
 *             @OA\Property(property="permanent_registration_address", type="string", example=""),
 *             @OA\Property(property="temporary_registration_address", type="string", example=""),
 *             @OA\Property(property="actual_residence_address", type="string", example=""),
 *             @OA\Property(property="family_partner", type="string", example=""),
 *             @OA\Property(property="adult_family_members", type="string", example=""),
 *             @OA\Property(property="adult_children", type="string", example=""),
 *             @OA\Property(property="serviceman", type="bool", example=""),
 *             @OA\Property(property="law_breaker", type="string", example=""),
 *             @OA\Property(property="legal_entity", type="string", example=""),
 *             @OA\Property(property="is_data_processing", type="bool", example=""),
 *             @OA\Property(property="comment", type="string", example=""),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             @OA\Property(property="key", type="string", example=""),
 *             @OA\Property(property="vacancies_key", type="string", example=""),
 *              @OA\Property(property="marital_statuses_key", type="string", example=""),
 *              @OA\Property(property="status", type="string", example=""),
 *              @OA\Property(property="first_name", type="string", example=""),
 *              @OA\Property(property="last_name", type="string", example=""),
 *              @OA\Property(property="middle_name", type="string", example=""),
 *              @OA\Property(property="reason_for_changing_surnames", type="string", example=""),
 *              @OA\Property(property="city_work", type="string", example="Новосибирск"),
 *              @OA\Property(property="birth_date", type="string", example=""),
 *              @OA\Property(property="country_birth", type="string", example=""),
 *              @OA\Property(property="city_birth", type="string", example=""),
 *              @OA\Property(property="level_educational", type="string", example=""),
 *              @OA\Property(property="courses", type="string", example=""),
 *              @OA\Property(property="educational_institution", type="string", example=""),
 *              @OA\Property(property="mobile_phone_candidate", type="string", example=""),
 *              @OA\Property(property="home_phone_candidate", type="string", example=""),
 *              @OA\Property(property="mail_candidate", type="string", example=""),
 *              @OA\Property(property="inn", type="string", example=""),
 *              @OA\Property(property="passport_series", type="string", example=""),
 *              @OA\Property(property="passport_number", type="string", example=""),
 *              @OA\Property(property="passport_issued", type="string", example=""),
 *              @OA\Property(property="permanent_registration_address", type="string", example=""),
 *              @OA\Property(property="temporary_registration_address", type="string", example=""),
 *              @OA\Property(property="actual_residence_address", type="string", example=""),
 *              @OA\Property(property="family_partner", type="string", example=""),
 *              @OA\Property(property="adult_family_members", type="string", example=""),
 *              @OA\Property(property="adult_children", type="string", example=""),
 *              @OA\Property(property="serviceman", type="bool", example=""),
 *              @OA\Property(property="law_breaker", type="string", example=""),
 *              @OA\Property(property="legal_entity", type="string", example=""),
 *              @OA\Property(property="is_data_processing", type="bool", example=""),
 *              @OA\Property(property="comment", type="string", example=""),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Ошибка отправки анкеты")
 *         )
 *     )
 * )
 *
 * @param CandidateProfilesUpdateRequest $request
 * @return JsonResponse
 */
class CandidateProfileUpdateController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(CandidateProfilesUpdateRequest $request)
    {
        $attributes = $request->validated();
        $candidateProfile =  $this->candidateProfilesRepository->findByKey($attributes['key']);
        $repository = $this->candidateProfilesRepository->update($candidateProfile, $attributes);
        $dataCollection = new CandidateProfileResource($repository->load('ropCandidates.ropAccount'));

        return new JsonResponse(
            data: $dataCollection,
            status: Response::HTTP_CREATED
        );
    }
}
