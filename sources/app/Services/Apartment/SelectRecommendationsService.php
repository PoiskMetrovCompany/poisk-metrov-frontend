<?php

namespace App\Services\Apartment;

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
    )
    {

    }

    public function getPersonalRecommendations(string $userKey, string $pageCode): array
    {
        $codes = new Collection();
        $visitedPages = $this->visitedPageRepository->getMetrics($userKey, $pageCode, $codes);
    }

    public function getGeneralRecommendations(): array
    {

    }

}
