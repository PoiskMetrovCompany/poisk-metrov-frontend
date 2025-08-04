<?php

namespace App\Core\Interfaces\Services;

interface AdsAgreementServiceInterface
{
    /**
     * @param string $phone
     * @param $name
     * @return void
     */
    public function setAdsAgreement(string $phone, $name): void;
}
