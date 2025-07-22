<?php

namespace App\Core\DTO\Exports;

final class CandidatesProfilesDTO
{
    public string $vacancyTitle;
    public string $status;
    public string $firstName;
    public string $lastName;
    public string $middleName;
    public string $reasonForChangingSurnames;
    public string $birthDate;
    public string $countryBirth;
    public string $cityBirth;
    public string $mobilePhoneCandidate;
    public string $homePhoneCandidate;
    public string $mailCandidate;
    public string $inn;
    public string $passportSeries;
    public string $passportNumber;
    public string $passportIssued;
    public string $permanentRegistrationAddress;
    public string $temporaryRegistrationAddress;
    public string $actualResidenceAddress;
    public string $maritalStatusesTitle;
    public string $familyPartner;
    public string $adultFamilyMembers;
    public string $adultChildren;
    public bool $serviceman;
    public string $lawBreaker;
    public string $legalEntity;
    public bool $isDataProcessing;
    public ?string $comment;

    public function __construct(array $attributes)
    {
        $this->vacancyTitle               = $attributes['vacancy_title'] ?? '';
        $this->status                     = $attributes['status'] ?? '';
        $this->firstName                  = $attributes['first_name'] ?? '';
        $this->lastName                   = $attributes['last_name'] ?? '';
        $this->middleName                 = $attributes['middle_name'] ?? '';
        $this->reasonForChangingSurnames  = $attributes['reason_for_changing_surnames'] ?? '';
        $this->birthDate                  = $attributes['birth_date'] ?? '';
        $this->countryBirth               = $attributes['country_birth'] ?? '';
        $this->cityBirth                  = $attributes['city_birth'] ?? '';
        $this->mobilePhoneCandidate       = $attributes['mobile_phone_candidate'] ?? '';
        $this->homePhoneCandidate         = $attributes['home_phone_candidate'] ?? '';
        $this->mailCandidate              = $attributes['mail_candidate'] ?? '';
        $this->inn                        = $attributes['inn'] ?? '';
        $this->passportSeries             = $attributes['passport_series'] ?? '';
        $this->passportNumber             = $attributes['passport_number'] ?? '';
        $this->passportIssued             = $attributes['passport_issued'] ?? '';
        $this->permanentRegistrationAddress = $attributes['permanent_registration_address'] ?? '';
        $this->temporaryRegistrationAddress = $attributes['temporary_registration_address'] ?? '';
        $this->actualResidenceAddress     = $attributes['actual_residence_address'] ?? '';
        $this->maritalStatusesTitle       = $attributes['marital_statuses_title'] ?? '';
        $this->familyPartner              = $attributes['family_partner'] ?? '';
        $this->adultFamilyMembers         = $attributes['adult_family_members'] ?? '';
        $this->adultChildren              = $attributes['adult_children'] ?? '';
        $this->serviceman                 = (bool)($attributes['serviceman'] ?? false);
        $this->lawBreaker                 = $attributes['law_breaker'] ?? '';
        $this->legalEntity                = $attributes['legal_entity'] ?? '';
        $this->isDataProcessing           = (bool)($attributes['is_data_processing'] ?? true);
        $this->comment                    = $attributes['comment'] ?? null;
    }
}
