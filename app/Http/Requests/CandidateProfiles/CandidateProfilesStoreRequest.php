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
            'vacancies_key' => ['required'],
            'marital_statuses_key' => ['required'],
            'status' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'reason_for_changing_surnames' => ['nullable'],
            'city_work' => ['required', 'string', 'max:255'],
            'birth_date' => ['required'],
            'country_birth' => ['required', 'string', 'max:255'],
            'city_birth' => ['required', 'string', 'max:255'],
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

            'mobile_phone_candidate' => ['required', 'string', 'max:100'],
            'home_phone_candidate' => ['required', 'string', 'max:100'],
            'mail_candidate' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'string', 'max:20'],
            'passport_series' => ['required', 'string', 'max:4'],
            'passport_number' => ['required', 'string', 'max:6'],
            'passport_issued' => ['required', 'string', 'max:255'],
            'permanent_registration_address' => ['required', 'string', 'max:255'],
            'temporary_registration_address' => ['required', 'string', 'max:255'],
            'actual_residence_address' => ['required', 'string', 'max:255'],
            'family_partner' => ['nullable'],
            'adult_family_members' => ['required'],
            'adult_children' => ['required'],
            'serviceman' => ['nullable', 'boolean'],
            'law_breaker' => ['required', 'string', 'max:255'],
            'legal_entity' => ['required', 'string', 'max:255'],
            'is_data_processing' => ['nullable', 'boolean'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
