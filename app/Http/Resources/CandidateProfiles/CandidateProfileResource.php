<?php

namespace App\Http\Resources\CandidateProfiles;

use App\Models\MaritalStatuses;
use App\Models\Vacancies;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'vacancy' => [
                'key' => $this->vacancies_key,
                'attributes' => Vacancies::where(['key' => $this->vacancies_key])->first(),
            ],
            'marital_statuses' => [
                'key' => $this->marital_statuses_key,
                'attributes' => MaritalStatuses::where(['key' => $this->marital_statuses_key])->first(),
            ],
            'status' => $this->status,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'reason_for_changing_surnames' => $this->reason_for_changing_surnames,
            'birth_date' => $this->birth_date,
            'country_birth' => $this->country_birth,
            'city_birth' => $this->city_birth,
            'mobile_phone_candidate' => $this->mobile_phone_candidate,
            'home_phone_candidate' => $this->home_phone_candidate,
            'mail_candidate' => $this->mail_candidate,
            'inn' => $this->inn,
            'passport_series' => $this->passport_series,
            'passport_number' => $this->passport_number,
            'passport_issued' => $this->passport_issued,
            'permanent_registration_address' => $this->permanent_registration_address,
            'temporary_registration_address' => $this->temporary_registration_address,
            'actual_residence_address' => $this->actual_residence_address,
            'family_partner' => $this->family_partner,
            'adult_family_members' => $this->adult_family_members,
            'adult_children' => $this->adult_children,
            'serviceman' => $this->serviceman,
            'law_breaker' => $this->law_breaker,
            'legal_entity' => $this->legal_entity,
            'is_data_processing' => $this->is_data_processing,
            'comment' => $this->comment,
        ];
    }
}
