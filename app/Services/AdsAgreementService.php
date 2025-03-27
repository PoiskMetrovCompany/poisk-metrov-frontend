<?php

namespace App\Services;
use App\Core\Services\AdsAgreementServiceInterface;
use App\Models\UserAdsAgreement;

/**
 * Class AdsAgreementService.
 */
class AdsAgreementService extends AbstractService implements AdsAgreementServiceInterface
{
    public function setAdsAgreement(string $phone, $name): void
    {
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
