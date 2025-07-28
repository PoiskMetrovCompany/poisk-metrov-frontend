<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountLoginRequest;
use App\Http\Requests\CandidateProfiles\CandidateProfilesStoreRequest;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use App\Jobs\SetChangesCandidatesQuestionnaireQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"CandidateProfiles"},
 *     path="/api/v1/candidates/store",
 *     summary="Отправка анкеты кандидата.",
 *     description="Возвращение JSON объекта",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные для отправки анкеты кандидата",
 *         @OA\JsonContent(
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
 *             @OA\Property(property="vacancies_key", type="string", example=""),
 *              @OA\Property(property="marital_statuses_key", type="string", example=""),
 *              @OA\Property(property="status", type="string", example=""),
 *              @OA\Property(property="first_name", type="string", example=""),
 *              @OA\Property(property="last_name", type="string", example=""),
 *              @OA\Property(property="middle_name", type="string", example=""),
 *              @OA\Property(property="reason_for_changing_surnames", type="string", example=""),
 *              @OA\Property(property="birth_date", type="string", example=""),
 *              @OA\Property(property="country_birth", type="string", example=""),
 *              @OA\Property(property="city_birth", type="string", example=""),
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
 * @param CandidateProfilesStoreRequest $request
 * @return JsonResponse
 */
class CandidateProfileStoreController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(CandidateProfilesStoreRequest $request)
    {
        $attributes = $request->validated();
        $attributes['key'] = Str::uuid()->toString();
        $candidateProfile = $this->candidateProfilesRepository->store($attributes);

        // SetChangesCandidatesQuestionnaireQueue::dispatch($candidateProfile);

        DB::connection('pm-log')
            ->table('candidate_profiles_has')
            ->insert([
                'profile_key' => $attributes['key'],
                'title' => 'Новая анкета',
                'is_visible' => false,
                'meta_attributes' => $attributes,
            ]);

        $dataCollection = new CandidateProfileResource($candidateProfile);

        return new JsonResponse(
            data: $dataCollection,
            status: Response::HTTP_CREATED
        );
    }
}
