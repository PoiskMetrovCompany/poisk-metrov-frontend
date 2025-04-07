<?php

namespace App\Services;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\DeletedFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\RelationshipEntityRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoriteBuildingRepositoryInterface;
use App\Core\Interfaces\Repositories\UserFavoritePlanRepositoryInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\FavoritesServiceInterface;
use App\Core\Interfaces\Services\PriceFormattingServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Models\CRMSyncRequiredForUser;
use App\Models\DeletedFavoriteBuilding;
use App\Models\ResidentialComplex;
use App\Models\UserFavoriteBuilding;
use App\Models\UserFavoritePlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/**
 * @package App\Services
 * @implements FavoritesServiceInterface
 * @property-read PriceFormattingServiceInterface $priceFormattingService
 * @property-read RealEstateServiceInterface $realEstateService
 * @property-read ApartmentServiceInterface $apartmentService
 * @property-read UserFavoritePlanRepositoryInterface $userFavoritePlanRepository
 * @property-read UserFavoriteBuildingRepositoryInterface $userFavoriteBuildingRepository
 * @property-read DeletedFavoriteBuildingRepositoryInterface $deletedFavoriteBuildingRepository
 * @property-read ResidentialComplexRepositoryInterface $residentialComplexRepository
 * @property-read ApartmentRepositoryInterface $apartmentRepository
 * @property-read RelationshipEntityRepositoryInterface $relationshipEntityRepository
 */
final class FavoritesService implements FavoritesServiceInterface
{
    public function __construct(
        protected PriceFormattingServiceInterface $priceFormattingService,
        protected RealEstateServiceInterface $realEstateService,
        protected ApartmentServiceInterface $apartmentService,
        protected UserFavoritePlanRepositoryInterface $userFavoritePlanRepository,
        protected UserFavoriteBuildingRepositoryInterface $userFavoriteBuildingRepository,
        protected DeletedFavoriteBuildingRepositoryInterface $deletedFavoriteBuildingRepository,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface $apartmentRepository,
        protected RelationshipEntityRepositoryInterface $relationshipEntityRepository,
    ) {
    }

    public function getBuildingCodesForFavoritePlans(): array
    {
        $offerIds = $this->getFavoritePlanOfferIds();
        $buildingIds = $this->apartmentRepository->findByOfferId($offerIds)->pluck('complex_id')->unique();

        return ResidentialComplex::whereIn('id', $buildingIds)->get()->pluck('code')->toArray();
    }

    public function getFavoritePlanOfferIds(): array
    {
        $user = Auth::user();
        $offerIds = [];

        if (key_exists('favoritePlans', $_COOKIE)) {
            $offerIds = explode(',', $_COOKIE['favoritePlans']);
        }

        if ($user != null) {
            $favoritePlansIds = $user->favoritePlans()->get()->pluck('offer_id');
            $offerIds = $favoritePlansIds->merge($offerIds)->unique()->toArray();
        }

        return $offerIds;
    }

    public function getFavoritePlanData(): array
    {
        $offerIds = $this->getFavoritePlanOfferIds();
        $apartments = $this->apartmentRepository->findByOfferIdBuilder($offerIds, 'price')
            ->orderBy('price')
            ->get();
        return ApartmentResource::collection($apartments)->toArray(new Request());
    }

    public function getBuildingsForFavoritePlans(): array
    {
        $offerIds = $this->getFavoritePlanOfferIds();
        $buildingIds = $this->apartmentRepository->findByOfferId($offerIds)->pluck('complex_id')->unique();
         return $this->residentialComplexRepository->findInBuildingId($buildingIds)->pluck('code')->toArray();
    }

    public function getBuildingDataSorted(array $codes, string $parameter, string $order): mixed
    {
        return $this->relationshipEntityRepository->buildingSort($codes, $parameter, $order);
    }

    public function getFavoriteBuildingCodes(): array
    {
        $user = Auth::user();

        $buildingCodes = [];

        if (key_exists('favoriteBuildings', $_COOKIE)) {
            $buildingCodes = explode(',', $_COOKIE['favoriteBuildings']);
        }

        if ($user != null) {
            $favoriteBuildingCodes = $user->favoriteBuildings()->get()->pluck('complex_code');
            $buildingCodes = $favoriteBuildingCodes->merge($buildingCodes)->unique()->toArray();
        }

        return $buildingCodes;
    }

    public function syncCookiesWithFavorites(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        if ($user->favoritePlans()->count() == 0) {
            $this->syncFavoriteApartmentsWithCookies();
        }

        //Load updated favorites table into a new cookie
        $userFavoritePlans = $user->favoritePlans;
        $favoriteApartmentCodes = $userFavoritePlans->pluck('offer_id')->toArray();
        $favoritePlansCookie = Cookie::forever('favoritePlans', implode(',', $favoriteApartmentCodes))->withRaw(true)->withHttpOnly(false);

        if ($user->favoriteBuildings()->count() == 0) {
            $this->syncFavoriteBuildingsWithCookies();
        }

        //Load updated favorites table into a new cookie
        $userFavoriteBuildings = $user->favoriteBuildings;
        $favoriteBuildingCodes = $userFavoriteBuildings->pluck('complex_code')->toArray();
        $favoriteBuildingsCookie = Cookie::forever('favoriteBuildings', implode(',', $favoriteBuildingCodes))->withRaw(true)->withHttpOnly(false);

        return [$favoritePlansCookie, $favoriteBuildingsCookie];
    }

    public function syncFavoritesWithCookies(): void
    {
        $this->syncFavoriteApartmentsWithCookies();
        $this->syncFavoriteBuildingsWithCookies();
    }

    public function syncDeletedFavoritesWithCookies(): void
    {
        $userId = Auth::id();

        if (! $userId) {
            return;
        }

        $removedFavoriteBuildings = Cookie::get('removedFavoriteBuildings');

        if ($removedFavoriteBuildings != null) {
            $removedFavoriteBuildings = explode(',', $removedFavoriteBuildings);

            foreach ($removedFavoriteBuildings as $removed) {
                DeletedFavoriteBuilding::createForCurrentUser($removed);
            }
        }
    }

    public function syncFavoriteApartmentsWithCookies()
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $offerIds = [];

        if (key_exists('favoritePlans', $_COOKIE)) {
            $userFavoritePlans = $user->favoritePlans;
            $favoriteApartmentCodes = $userFavoritePlans->pluck('offer_id')->toArray();
            $offerIds = explode(',', $_COOKIE['favoritePlans']);

            foreach ($offerIds as $offer) {
                //If plan from cookies is not in favorite plans and apartment still exists, create it
                if (! in_array($offer, $favoriteApartmentCodes) &&
                    $this->apartmentRepository->isExists(['offer_id' => $offer]) &&
                    !$this->userFavoritePlanRepository->isExists(['user_id' => $user->id, 'offer_id' => $offer])) {
                    $this->userFavoritePlanRepository->store(['user_id' => $user->id, 'offer_id' => $offer]);
                }
            }
        }

        setrawcookie('cachedFavoritePlansCount', '-1', time() - 100000, '/');
    }

    public function syncFavoriteBuildingsWithCookies()
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $buildingCodes = [];

        if (key_exists('favoriteBuildings', $_COOKIE)) {
            $userFavoriteBuildings = $user->favoriteBuildings;
            $favoriteBuildingCodes = $userFavoriteBuildings->pluck('complex_code')->toArray();
            $buildingCodes = explode(',', $_COOKIE['favoriteBuildings']);

            foreach ($buildingCodes as $code) {
                if (! in_array($code, $favoriteBuildingCodes) &&
                    $this->residentialComplexRepository->isExists(['code' => $code]) &&
                    !$this->userFavoriteBuildingRepository->isExists(['user_id' => $user->id, 'complex_code' => $code])) {
                    $this->userFavoriteBuildingRepository->store([
                        'user_id' => $user->id,
                        'complex_code' => $code
                    ]);
                }
            }
        }

        setrawcookie('cachedFavoriteBuildingsCount', '-1', time() - 100000, '/');
    }

    public function switchLike(string $type, string $code, string $action): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return $this->countFavoritesDetailed();
        }

        switch ($type) {
            case 'apartment':
                switch ($action) {
                    case 'add':
                        if (!$this->userFavoritePlanRepository->isExists(['offer_id' => $code])) {
                            $this->userFavoritePlanRepository->store(['user_id' => $user->id, 'offer_id' => $code]);
                            setrawcookie('cachedFavoritePlansCount', '-1', time() - 100000, '/');
                        }
                        break;
                    case 'remove':
                        if ($this->userFavoritePlanRepository->isExists(['offer_id' => $code])) {
                            $builder = $this->userFavoritePlanRepository->find(['user_id' => $user->id, 'offer_id' => $code])->delete();
                            setrawcookie('cachedFavoritePlansCount', '-1', time() - 100000, '/');
                        }
                        break;
                }
                break;
            case 'building':
                switch ($action) {
                    case 'add':
                        if (!$this->userFavoriteBuildingRepository->isExists(['complex_code' => $code])) {
                            $this->userFavoriteBuildingRepository->store(['user_id' => $user->id, 'complex_code' => $code]);
                            setrawcookie('cachedFavoriteBuildingsCount', '-1', time() - 100000, '/');
                        }
                        break;
                    case 'remove':
                        if ($this->userFavoriteBuildingRepository->isExists(['complex_code' => $code])) {
                            $this->userFavoriteBuildingRepository->find(['user_id' => $user->id, 'complex_code' => $code])->delete();

                            if (!$this->deletedFavoriteBuildingRepository->isExists(['user_id' => $user->id, 'complex_code' => $code])) {
                                $this->deletedFavoriteBuildingRepository->store([
                                    'user_id' => $user->id,
                                    'complex_code' => $code
                                ]);
                            }

                            setrawcookie('cachedFavoriteBuildingsCount', '-1', time() - 100000, '/');
                        }
                        break;
                }
                break;
        }

        CRMSyncRequiredForUser::createForCurrentUser();

        return $this->countFavoritesDetailed();
    }

    public function countFavoritesDetailed(): JsonResponse
    {
        $favoritePlans = $this->countFavoritePlans();
        $favoriteBuildings = $this->countFavoriteBuildings();

        return response()->json([
            'plans' => $favoritePlans,
            'buildings' => $favoriteBuildings,
            'total' => ($favoriteBuildings + $favoritePlans)
        ]);
    }

    public function countFavorites(): int
    {
        $favPlans = $this->countFavoritePlans();
        $favBuildings = $this->countFavoriteBuildings();
        $count = $favPlans + $favBuildings;

        return $count;
    }

    public function countFavoritePlans(): int
    {
        $cachedFavoritePlansCount = Cookie::get('cachedFavoritePlansCount', null);

        if ($cachedFavoritePlansCount == null) {
            $cachedFavoritePlansCount = count($this->getFavoritePlanOfferIds());
            setrawcookie('cachedFavoritePlansCount', $cachedFavoritePlansCount, time() + 31536000, '/');
        }

        return $cachedFavoritePlansCount;
    }

    public function countFavoriteBuildings(): int
    {
        $cachedFavoriteBuildingsCount = Cookie::get('cachedFavoriteBuildingsCount', null);

        if ($cachedFavoriteBuildingsCount == null) {
            $cachedFavoriteBuildingsCount = count($this->getFavoriteBuildingCodes());
            setrawcookie('cachedFavoriteBuildingsCount', $cachedFavoriteBuildingsCount, time() + 31536000, '/');
        }

        return $cachedFavoriteBuildingsCount;
    }
}
