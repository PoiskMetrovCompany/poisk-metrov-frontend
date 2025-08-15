<?php

namespace App\Http\Requests\CandidateProfiles;

use Illuminate\Foundation\Http\FormRequest;

class CandidateProfilesStoreRequest extends FormRequest
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
            'level_educational' => ['nullable', 'string', 'max:255'],
            'courses' => ['nullable'],
            'educational_institution' => ['nullable'],

            'organization_name' => ['nullable'],
            'organization_phone' => ['nullable'],
            'field_of_activity' => ['nullable'],
            'organization_address' => ['nullable'],
            'organization_job_title' => ['nullable'],
            'organization_price' => ['nullable'],
            'date_of_hiring' => ['nullable'],
            'date_of_dismissal' => ['nullable'],
            'reason_for_dismissal' => ['nullable'],
            'recommendation_contact' => ['nullable'],

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
