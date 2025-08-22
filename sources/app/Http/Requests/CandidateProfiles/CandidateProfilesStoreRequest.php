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
            'work_team' => ['required'],
            'status' => ['nullable'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'reason_for_changing_surnames' => ['nullable'],
            'city_work' => ['required', 'string', 'max:255'],
            'birth_date' => ['required'],
            'country_birth' => ['required', 'string', 'max:255'],
            'city_birth' => ['nullable', 'string', 'max:255'],
            'level_educational' => ['nullable', 'string', 'max:255'],
            'courses' => ['nullable'],
            'educational_institution' => ['nullable'],
            'organization_name' => ['required'],
            'organization_phone' => ['required'],
            'field_of_activity' => ['required'],
            'organization_address' => ['required'],
            'organization_job_title' => ['required'],
            'organization_price' => ['required'],
            'date_of_hiring' => ['required'],
            'date_of_dismissal' => ['required'],
            'reason_for_dismissal' => ['required'],
            'recommendation_contact' => ['nullable'],
            'mobile_phone_candidate' => ['required', 'string', 'max:100'],
            'home_phone_candidate' => ['nullable', 'string', 'max:100'],
            'mail_candidate' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'string', 'max:20'],
            'passport_series' => ['required', 'string', 'max:4'],
            'passport_number' => ['required', 'string', 'max:6'],
            'passport_issued' => ['required', 'string', 'max:255'],
            'permanent_registration_address' => ['nullable', 'string', 'max:255'],
            'temporary_registration_address' => ['nullable', 'string', 'max:255'],
            'actual_residence_address' => ['nullable', 'string', 'max:255'],
            'family_partner' => ['nullable'],
            'adult_family_members' => ['nullable'],
            'adult_children' => ['nullable'],
            'serviceman' => ['required', 'boolean'],
            'law_breaker' => ['required', 'string', 'max:255'],
            'legal_entity' => ['required', 'string', 'max:255'],
            'is_data_processing' => ['required', 'boolean'],
            'comment' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        $errorMessageDefault = 'Обязательно для заполнения';
        return [
            'city_work.required' => $errorMessageDefault,
            'work_team.required' => $errorMessageDefault,
            'first_name.required' => $errorMessageDefault,
            'last_name.required' => $errorMessageDefault,
            'middle_name.required' => $errorMessageDefault,
            'vacancies_key.required' => $errorMessageDefault,
            'marital_statuses_key.required' => $errorMessageDefault,
            'birth_date.required' => $errorMessageDefault,
            'country_birth.required' => $errorMessageDefault,
            'mobile_phone_candidate.required' => $errorMessageDefault,
            'mail_candidate.required' => $errorMessageDefault,
            'inn.required' => $errorMessageDefault,
            'organization_name.required' => $errorMessageDefault,
            'organization_phone.required' => $errorMessageDefault,
            'field_of_activity.required' => $errorMessageDefault,
            'organization_address.required' => $errorMessageDefault,
            'organization_job_title.required' => $errorMessageDefault,
            'organization_price.required' => $errorMessageDefault,
            'date_of_hiring.required' => $errorMessageDefault,
            'date_of_dismissal.required' => $errorMessageDefault,
            'reason_for_dismissal.required' => $errorMessageDefault,
            'passport_series.required' => $errorMessageDefault,
            'passport_number.required' => $errorMessageDefault,
            'passport_issued.required' => $errorMessageDefault,
            'permanent_registration_address.required' => $errorMessageDefault,
            'actual_residence_address.required' => $errorMessageDefault,
            'serviceman.required' => $errorMessageDefault,
            'legal_entity.required' => $errorMessageDefault,
            'is_data_processing.required' => $errorMessageDefault,
        ];
    }
}
