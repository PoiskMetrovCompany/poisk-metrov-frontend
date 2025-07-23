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
            'vacancies_key' => ['required'],
            'marital_statuses_key' => ['required'],
            'status' => ['required'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'reason_for_changing_surnames' => ['nullable'],
            'birth_date' => ['required'],
            'country_birth' => ['required', 'string', 'max:255'],
            'city_birth' => ['required', 'string', 'max:255'],
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
            'family_partner' => ['required'],
            'adult_family_members' => ['required'],
            'adult_children' => ['required'],
            'serviceman' => ['nullable', 'boolean'],
            'law_breaker' => ['required', 'string', 'max:255'],
            'legal_entity' => ['required', 'string', 'max:255'],
            'is_data_processing' => ['nullable', 'boolean'],
            'comment' => ['required', 'string'],
        ];
    }
}
