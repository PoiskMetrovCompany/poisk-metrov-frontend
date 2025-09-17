<?php

namespace App\Http\Resources\CandidateProfiles;

use App\Models\Account;
use App\Models\MaritalStatuses;
use App\Models\ROPCandidate;
use App\Models\Vacancies;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateProfileResource extends JsonResource
{
    public function toArray(Request $request)
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
            'work_team' => $this->getWorkTeamString(),
            'status' => $this->status,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'reason_for_changing_surnames' => $this->reason_for_changing_surnames,
            'city_work' => $this->city_work,
            'birth_date' => $this->birth_date,
            'country_birth' => $this->country_birth,
            'city_birth' => $this->city_birth,
            'level_educational' => $this->level_educational,
            'courses' => $this->courses,
            'educational_institution' => $this->educational_institution,

            'organization_name' => $this->organization_name,
            'organization_phone' => $this->organization_phone,
            'field_of_activity' => $this->field_of_activity,
            'organization_address' => $this->organization_address,
            'organization_job_title' => $this->organization_job_title,
            'organization_price' => $this->organization_price,
            'date_of_hiring' => $this->date_of_hiring,
            'date_of_dismissal' => $this->date_of_dismissal,
            'reason_for_dismissal' => $this->reason_for_dismissal,
            'recommendation_contact' => $this->recommendation_contact,

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
            'family_partner' => json_decode($this->family_partner, true),
            'adult_family_members' => json_decode($this->adult_family_members, true),
            'adult_children' => json_decode($this->adult_children, true),
            'serviceman' => $this->serviceman,
            'law_breaker' => $this->law_breaker,
            'legal_entity' => $this->legal_entity,
            'is_data_processing' => $this->is_data_processing,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get work team string in format "LastName F."
     */
    private function getWorkTeamString()
    {
        $ropCandidate = $this->ropCandidates->first();

        if (!$ropCandidate) {
            return '';
        }

        $ropAccount = $ropCandidate->ropAccount;

        if (!$ropAccount) {
            return '';
        }

        $firstNameInitial = mb_substr($ropAccount->first_name, 0, 1);

        return $ropAccount->last_name . ' ' . $firstNameInitial . '.';
    }
}
