<?php

namespace App\Services;
use App\Models\UserAdsAgreement;

/**
 * Class AdsAgreementService.
 */
class AdsAgreementService extends AbstractService
{
    public function setAdsAgreement(string $phone, $name) {
        $data = [
            'phone' => $phone,
            'agreement' => true
        ];

        if ($name != null && $name != 'undefined' && $name != '') {
            $data['name'] = $name;
        }

        UserAdsAgreement::create($data);
    }
}