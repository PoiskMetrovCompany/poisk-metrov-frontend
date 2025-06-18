<?php

namespace App\Core\Interfaces\Services;

interface PDFServiceInterface
{
    /**
     * @return mixed
     */
    public function getFavoriteBuildingsPresentation(): mixed;

    /**
     * @return mixed
     */
    public function getFavoriteApartmentsPresentation(): mixed;

    /**
     * @param array $buildingCodes
     * @return array
     */
    public function downloadBuildingPdf(array $buildingCodes): array;

    /**
     * @param array $apartmentCodes
     * @return array
     */
    public function downloadApartmentPdf(array $apartmentCodes): array;

    /**
     * @return array
     */
    public function getFavoriteApartments(): array;

    /**
     * @return array
     */
    public function getFavoriteBuildings(): array;

    /**
     * @param array $offerIds
     * @return array
     */
    public function getApartmentsData(array $offerIds): array;

    /**
     * @param array $buildingCodes
     * @return array
     */
    public function getComplexesData(array $buildingCodes): array;
}
