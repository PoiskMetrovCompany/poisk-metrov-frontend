<?php

namespace App\Services;
use AllowDynamicProperties;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Models\UserAdsAgreement;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements AdsAgreementServiceInterface
 * @property-read UserAdsAgreementRepositoryInterface $userAdsAgreementRepository
 */
final class AdsAgreementService extends AbstractService implements AdsAgreementServiceInterface
{
    public function __construct(protected UserAdsAgreementRepositoryInterface $userAdsAgreementRepository)
    {

    }
    public function setAdsAgreement(string $phone, $name): void
    {
        $data = [
            'phone' => $phone,
            'agreement' => true
        ];

        if ($name != null && $name != 'undefined' && $name != '') {
            $data['name'] = $name;
        }

        $this->userAdsAgreementRepository->store($data);
    }
}
