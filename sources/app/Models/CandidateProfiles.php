<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfiles extends Model
{
    use HasFactory;
    protected $table = "candidate_profiles";

    /**
     * @var string[]
     */
    protected $fillable = [
        'key',
        'vacancies_key',
        'marital_statuses_key',
        'work_team',
        'first_name',
        'last_name',
        'middle_name',
        'reason_for_changing_surnames',
        'city_work',
        'birth_date',
        'country_birth',
        'city_birth',
        'level_educational',
        'courses',
        'educational_institution',
        'organization_name',
        'organization_phone',
        'field_of_activity',
        'organization_address',
        'organization_job_title',
        'organization_price',
        'date_of_hiring',
        'date_of_dismissal',
        'reason_for_dismissal',
        'recommendation_contact',
        'mobile_phone_candidate',
        'home_phone_candidate',
        'mail_candidate',
        'inn',
        'passport_series',
        'passport_number',
        'passport_issued',
        'permanent_registration_address',
        'temporary_registration_address',
        'actual_residence_address',
        'family_partner',
        'adult_family_members',
        'adult_children',
        'serviceman',
        'law_breaker',
        'legal_entity',
        'is_data_processing',
        'comment',
    ];

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    public function vacancy() {
        return $this->belongsTo(Vacancies::class, 'vacancies_key', 'key');
    }

    public function maritalStatus() {
        return $this->belongsTo(MaritalStatuses::class, 'marital_statuses_key', 'key');
    }

    public function ropCandidates() {
        return $this->hasMany(ROPCandidate::class, 'candidate_key', 'key');
    }
}
