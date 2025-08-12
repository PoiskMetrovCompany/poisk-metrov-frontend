<?php

namespace App\Services\Apartment;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Core\Interfaces\Services\SelectRecommendationsServiceInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * @package App\Services\Apartment
 * @see AppServiceProvider::registerSelectRecommendationsService()
 * @implements SelectRecommendationsServiceInterface
 * @property-read static $mediumPrice
 * @property-read static $priceRange
 * @property-read static $mediumArea
 * @property-read static $areaRange
 * @property-read static $mediumRoomCount
 * @property-read static $roomCountRange
 * @property-read static $preferredRecommendationCount
 */
final class SelectRecommendationsService implements SelectRecommendationsServiceInterface
{
    private static int $mediumPrice = 10000000;
    private static int $priceRange = 4000000;
    private static int $mediumArea = 60;
    private static int $areaRange = 20;
    private static int $mediumRoomCount = 2;
    private static int $roomCountRange = 1;
    private static int $preferredRecommendationCount = 10;

    public function __construct(
        protected VisitedPageRepositoryInterface $visitedPageRepository,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface $apartmentRepository,
    ) {
    }

    public function getPersonalRecommendations(string $userKey, string $cityCode, bool $isAuthenticated = false): array
    {
        $visitedApartments = $this->visitedPageRepository->find(['user_key' => $userKey, 'type' => 'plan'])->get();
        $visitedBuildings = $this->visitedPageRepository->find(['user_key' => $userKey, 'type' => 'real-estate'])->get();

        $visitedApartmentOffers = $visitedApartments->pluck('offer_id');
        $visitedBuildingCodes = $visitedBuildings->pluck('complex_code');

        $apartments = $this->apartmentRepository->find(['offer_id' => $visitedApartmentOffers]);

        if (!empty($apartments)) {
            self::$mediumPrice = (int) $apartments->average('price');
            self::$mediumArea = (int) $apartments->average('area');
            self::$mediumRoomCount = (int) floor($apartments->average('room_count'));
        }
        $bestOffers = $this->residentialComplexRepository->getBestOffers($cityCode);
        $preferredBuildings = $this->residentialComplexRepository->find([
            'code' => $visitedBuildingCodes->toArray(),
            'location.code' => $cityCode
        ]);

        if ($visitedApartments->count() < 10 && $visitedBuildings->count() < 5) {
            $preferredBuildings = $bestOffers;
            self::$roomCountRange = 1;
        }

        $recommendations = new Collection();

        foreach ($preferredBuildings as $building) {
            $buildingApartments = $this->apartmentRepository->findByInComplexId([$building->id]);

            $recommendedApartment = $buildingApartments
                ->where('price', '>=', self::$mediumPrice - self::$priceRange)
                ->where('price', '<=', self::$mediumPrice + self::$priceRange)
                ->where('area', '>=', self::$mediumArea - self::$areaRange)
                ->where('area', '<=', self::$mediumArea + self::$areaRange)
                ->where('room_count', '>=', self::$mediumRoomCount - self::$roomCountRange)
                ->where('room_count', '<=', self::$mediumRoomCount + self::$roomCountRange)
                ->first();

            if ($recommendedApartment) {
                $recommendations->push($recommendedApartment);
            }

            if ($recommendations->count() >= self::$preferredRecommendationCount) {
                break;
            }
        }

        for ($i = $recommendations->count(); $i < self::$preferredRecommendationCount; $i++) {
            $building = $bestOffers->shift();
            if (!$building) {
                continue;
            }

            $buildingApartments = $this->apartmentRepository->findByInComplexId([$building->id]);

            $recommendedApartment = $buildingApartments
                ->where('room_count', '>=', self::$mediumRoomCount - self::$roomCountRange)
                ->where('room_count', '<=', self::$mediumRoomCount + self::$roomCountRange)
                ->whereNotIn('offer_id', $recommendations->pluck('offer_id'))
                ->first();

            if ($recommendedApartment) {
                $recommendations->push($recommendedApartment);
            }
        }

        return $recommendations->toArray();
    }

    public function getGeneralRecommendations(string $cityCode): array
    {
        $bestOffers = $this->residentialComplexRepository->getBestOffers($cityCode);
        $recommendations = new Collection();

        foreach ($bestOffers as $building) {
            $apartmentsInBuilding = $this->apartmentRepository->find(['key' => $building->key]);

            $recommendedApartment = $apartmentsInBuilding
                ->where('price', '>=', self::$mediumPrice - self::$priceRange)
                ->where('price', '<=', self::$mediumPrice + self::$priceRange)
                ->where('area', '>=', self::$mediumArea - self::$areaRange)
                ->where('area', '<=', self::$mediumArea + self::$areaRange)
                ->where('room_count', '>=', self::$mediumRoomCount - self::$roomCountRange)
                ->where('room_count', '<=', self::$mediumRoomCount + self::$roomCountRange)
                ->first();

            if ($recommendedApartment) {
                $recommendations->push($recommendedApartment);
            }

            if ($recommendations->count() >= self::$preferredRecommendationCount) {
                break;
            }
        }

        for ($i = $recommendations->count(); $i < self::$preferredRecommendationCount; $i++) {
            $building = $bestOffers->shift();
            if (!$building) continue;

            $apartmentsInBuilding = $this->apartmentRepository->findByKey($building->key);
            $recommendedApartment = $apartmentsInBuilding
                ->where('room_count', '>=', self::$mediumRoomCount - self::$roomCountRange)
                ->where('room_count', '<=', self::$mediumRoomCount + self::$roomCountRange)
                ->whereNotIn('offer_id', $recommendations->pluck('offer_id'))
                ->first();

            if ($recommendedApartment) {
                $recommendations->push($recommendedApartment);
            }
        }

        return $recommendations->toArray();
    }
}
