<?php

namespace App\Services;

use App\Core\Services\FavoritesServiceInterface;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Http\Resources\ApartmentResource;
use App\Models\CRMSyncRequiredForUser;
use App\Models\DeletedFavoriteBuilding;
use App\Models\UserFavoriteBuilding;
use App\Models\UserFavoritePlan;
use App\Services\ApartmentService;
use App\Services\PriceFormattingService;
use App\Services\RealEstateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

/**
 * Class FavoritesService.
 */
class FavoritesService implements FavoritesServiceInterface
{
    public function __construct(
        protected PriceFormattingService $priceFormattingService,
        protected RealEstateService $realEstateService,
        protected ApartmentService $apartmentService
    ) {
    }

    public function getBuildingCodesForFavoritePlans(): array
    {
        $offerIds = $this->getFavoritePlanOfferIds();
        $buildingIds = Apartment::whereIn('offer_id', $offerIds)->get()->pluck('complex_id')->unique();

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
        $apartments = Apartment::whereIn('offer_id', $offerIds)->orderBy('price')->get();

        return ApartmentResource::collection($apartments)->toArray(new Request());
    }

    public function getBuildingsForFavoritePlans(): array
    {
        $offerIds = $this->getFavoritePlanOfferIds();
        $buildingIds = Apartment::whereIn('offer_id', $offerIds)->get()->pluck('complex_id')->unique();

        return ResidentialComplex::whereIn('id', $buildingIds)->get()->pluck('code')->toArray();
    }

    public function getBuildingDataSorted(array $codes, string $parameter, string $order): mixed
    {
        $buildings = ResidentialComplex::whereIn('code', $codes)
            ->with('apartments')
            ->withCount('apartments')
            ->has('apartments')
            ->orderBy('apartments_count', 'DESC')
            ->get();

        $buildings = $buildings->sortBy(
            function (ResidentialComplex $building, int $key) use ($parameter, $order) {
                $price = $building
                    ->apartments
                    ->sortBy([[$parameter, $order]])
                    ->first()
                ->{"{$parameter}"};

                return $price;
            },
            SORT_NATURAL
        )->values();

        return $buildings;
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
                    Apartment::where('offer_id', $offer)->exists() &&
                    ! UserFavoritePlan::where(['user_id' => $user->id, 'offer_id' => $offer])->exists()) {
                    UserFavoritePlan::create(['user_id' => $user->id, 'offer_id' => $offer]);
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
                    ResidentialComplex::where('code', $code)->exists() &&
                    ! UserFavoriteBuilding::where(['user_id' => $user->id, 'complex_code' => $code])->exists()) {
                    UserFavoriteBuilding::create(['user_id' => $user->id, 'complex_code' => $code]);
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
                        if (! UserFavoritePlan::where('offer_id', $code)->exists()) {
                            UserFavoritePlan::create(['user_id' => $user->id, 'offer_id' => $code]);
                            setrawcookie('cachedFavoritePlansCount', '-1', time() - 100000, '/');
                        }
                        break;
                    case 'remove':
                        if (UserFavoritePlan::where('offer_id', $code)->exists()) {
                            UserFavoritePlan::where(['user_id' => $user->id, 'offer_id' => $code])->delete();
                            setrawcookie('cachedFavoritePlansCount', '-1', time() - 100000, '/');
                        }
                        break;
                }
                break;
            case 'building':
                switch ($action) {
                    case 'add':
                        if (! UserFavoriteBuilding::where('complex_code', $code)->exists()) {
                            UserFavoriteBuilding::create(['user_id' => $user->id, 'complex_code' => $code]);
                            setrawcookie('cachedFavoriteBuildingsCount', '-1', time() - 100000, '/');
                        }
                        break;
                    case 'remove':
                        if (UserFavoriteBuilding::where('complex_code', $code)->exists()) {
                            UserFavoriteBuilding::where(['user_id' => $user->id, 'complex_code' => $code])->delete();

                            if (! DeletedFavoriteBuilding::where(['user_id' => $user->id, 'complex_code' => $code])->exists()) {
                                DeletedFavoriteBuilding::create(['user_id' => $user->id, 'complex_code' => $code]);
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
