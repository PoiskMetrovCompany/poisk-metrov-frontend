<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Http\JsonResponse;

interface FavoritesServiceInterface
{
    /**
     * @return array
     */
    public function getBuildingCodesForFavoritePlans(): array;

    /**
     * @return array
     */
    public function getFavoritePlanOfferIds(): array;

    /**
     * @return array
     */
    public function getFavoritePlanData(): array;

    /**
     * @return array
     */
    public function getBuildingsForFavoritePlans(): array;

    /**
     * @param array $codes
     * @param string $parameter
     * @param string $order
     * @return mixed
     */
    public function getBuildingDataSorted(array $codes, string $parameter, string $order): mixed;

    /**
     * @return array
     */
    public function getFavoriteBuildingCodes(): array;

    /**
     * @return array
     */
    public function syncCookiesWithFavorites(): array;

    /**
     * @return void
     */
    public function syncFavoritesWithCookies(): void;

    /**
     * @return void
     */
    public function syncDeletedFavoritesWithCookies(): void;

    public function syncFavoriteApartmentsWithCookies();

    public function syncFavoriteBuildingsWithCookies();

    /**
     * @param string $type
     * @param string $code
     * @param string $action
     * @return JsonResponse
     */
    public function switchLike(string $type, string $code, string $action): JsonResponse;

    /**
     * @return JsonResponse
     */
    public function countFavoritesDetailed(): JsonResponse;

    /**
     * @param string $key
     * @return int
     */

    public function countFavorites(string $key): int;

    /**
     * @return int
     */
    public function countFavoritePlans(string $key): int;

    /**
     * @return int
     */
    public function countFavoriteBuildings(string $key): int;
}
