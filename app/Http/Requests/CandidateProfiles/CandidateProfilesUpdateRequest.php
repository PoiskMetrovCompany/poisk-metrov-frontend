<?php

namespace App\Http\Requests\CandidateProfiles;

use Illuminate\Foundation\Http\FormRequest;

class CandidateProfilesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'exists:candidate_profiles,key'],
            'vacancies_key' => ['nullable'],
            'marital_statuses_key' => ['nullable'],
            'status' => ['nullable'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'reason_for_changing_surnames' => ['nullable'],
            'city_work' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable'],
            'country_birth' => ['nullable', 'string', 'max:255'],
            'city_birth' => ['nullable', 'string', 'max:255'],
            'level_educational' => ['required', 'string', 'max:255'],
            'courses' => ['required'],
            'educational_institution' => ['required'],
            'organization_name' => ['required'],
            'organization_phone' => ['required'],
            'field_of_activity' => ['required'],
            'organization_address' => ['required'],
            'organization_job_title' => ['required'],
            'organization_price' => ['required'],
            'date_of_hiring' => ['required'],
            'date_of_dismissal' => ['required'],
            'reason_for_dismissal' => ['required'],
            'recommendation_contact' => ['required'],
            'mobile_phone_candidate' => ['nullable', 'string', 'max:100'],
            'home_phone_candidate' => ['nullable', 'string', 'max:100'],
            'mail_candidate' => ['nullable', 'string', 'max:255'],
            'inn' => ['nullable', 'string', 'max:20'],
            'passport_series' => ['nullable', 'string', 'max:4'],
            'passport_number' => ['nullable', 'string', 'max:6'],
            'passport_issued' => ['nullable', 'string', 'max:255'],
            'permanent_registration_address' => ['nullable', 'string', 'max:255'],
            'temporary_registration_address' => ['nullable', 'string', 'max:255'],
            'actual_residence_address' => ['nullable', 'string', 'max:255'],
            'family_partner' => ['nullable'],
            'adult_family_members' => ['nullable'],
            'adult_children' => ['nullable'],
            'serviceman' => ['nullable', 'boolean'],
            'law_breaker' => ['nullable', 'string', 'max:255'],
            'legal_entity' => ['nullable', 'string', 'max:255'],
            'is_data_processing' => ['nullable', 'boolean'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
