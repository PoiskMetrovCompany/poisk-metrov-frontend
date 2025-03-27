<?php

namespace App\Core\Services;

use Illuminate\Database\Eloquent\Builder;

interface BankServiceInterface
{
    /**
     * @param string $cityCode
     * @return void
     */
    public function downloadBanks(string $cityCode): void;

    /**
     * @param string $cityCode
     * @return void
     */
    public function parseBankPages(string $cityCode): void;

    /**
     * @param string $cityCode
     * @return void
     */
    public function parseTariffs(string $cityCode): void;

    /**
     * @param string $cityCode
     * @return void
     */
    public function makeBankTariffLists(string $cityCode): void;

    /**
     * @param string $cityCode
     * @return void
     */
    public function createBankLogos(string $cityCode): void;

    /**
     * @param string $cityCode
     * @return void
     */
    public function syncBanksAndTariffs(string $cityCode): void;

    /**
     * @param bool $asResource
     * @return array
     */
    public function getUniqueMortgageProgramData(bool $asResource = false): array;

    /**
     * @return mixed
     */
    public function getMortgageProgramDropdownData(): mixed;

    /**
     * @param string|null $cityCode
     */
    public function getBasicMortgageQueryBuilder(string|null $cityCode = null);

    /**
     * @return mixed
     */
    public function getMinimumAllowedMortgageAmount(): mixed;

    /**
     * @param string|null $cityCode
     * @return mixed
     */
    public function countPossibleMortgages(string|null $cityCode = null): mixed;

    /**
     * @param string $parameter
     * @param string $direction
     * @param array $categories
     * @param array $banks
     * @param float|int|null $preferredPrice
     * @param float|int|null $preferredYear
     * @param float|int|null $preferredInitialFee
     * @return Builder
     */
    public function getSortedMortgages(
        string $parameter,
        string $direction = 'asc',
        array $categories = [],
        array $banks = [],
        float|int|null $preferredPrice = null,
        float|int|null $preferredYear = null,
        float|int|null $preferredInitialFee = null
    ): Builder;

    /**
     * @param string|null $cityCode
     * @return mixed
     */
    public function getBankDropdownData(string|null $cityCode = null): mixed;

    /**
     * @param string|null $cityCode
     */
    public function getBanksForCity(string|null $cityCode = null);

    /**
     * @return array
     */
    public function getMaxMortgageParameters(): array;
}
