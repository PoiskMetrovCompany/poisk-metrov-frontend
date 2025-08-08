<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\ApartmentServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\RealEstateServiceInterface;
use App\Core\Interfaces\Services\VisitedPagesServiceInterface;
use App\Models\Apartment;
use App\Models\ApartmentHistory;
use App\Models\MortgageType;
use App\Models\Renovation;
use App\Models\ResidentialComplex;
use App\Models\UserFavoritePlan;
use App\Repositories\ResidentialComplexRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Log;

/**
 * Class ApartmentService.
 */
class ApartmentService extends AbstractService implements ApartmentServiceInterface
{
    public function __construct(
        protected VisitedPagesServiceInterface $visitedPagesService,
        protected RealEstateServiceInterface $realEstateService,
        protected CityServiceInterface $cityService,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository
    ) {
    }

    public function getApartmentRecommendations(): Collection
    {
        $visitedPages = $this->visitedPagesService->getVisitedApartments();
        $preferredBuildings = $this->visitedPagesService->getVisitedBuildings();
        $recommendations = new Collection();
        $visitedApartments = Apartment::whereIn('offer_id', $visitedPages);

        $visitedApartments->join('residential_complexes', 'residential_complexes.id', '=', 'apartments.complex_id');
        if (!Auth::user()) {
            $visitedApartments->whereNotIn('residential_complexes.builder', ResidentialComplex::$privateBuilders);
//        } else {
//            $visitedApartments->whereIn('residential_complexes.builder', ResidentialComplex::$privateBuilders);
        }
        $visitedApartments->get();
        $mediumPrice = 10000000;
        $priceRange = 4000000;
        $mediumArea = 60;
        $areaRange = 20;
        $mediumRoomCount = 2;
        $roomCountRange = 1;
        $preferredRecommendationCount = 10;
        $cityCode = $this->cityService->getUserCity();
        $bestOffers = $this->residentialComplexRepository->getBestOffers();

        $preferredBuildings = $this->residentialComplexRepository->getCode(['code', $preferredBuildings], $cityCode);

        if ($visitedPages->count() < 10 && $preferredBuildings->count() < 5) {
            $preferredBuildings = $bestOffers;
            $roomCountRange = 1;
        } else {
            $mediumPrice = $visitedApartments->average('price');
            $mediumArea = $visitedApartments->average('area');
            $mediumRoomCount = floor($visitedApartments->average('room_count'));
        }

        foreach ($preferredBuildings as $building) {
            $recommendedApartment = $building->apartments
                ->where('price', '>=', $mediumPrice - $priceRange)
                ->where('price', '<=', $mediumPrice + $priceRange)
                ->where('area', '>=', $mediumArea - $areaRange)
                ->where('area', '<=', $mediumArea + $areaRange)
                ->where('room_count', '>=', $mediumRoomCount - $roomCountRange)
                ->where('room_count', '<=', $mediumRoomCount + $roomCountRange)
                ->first();

            if ($recommendedApartment != null) {
                $recommendations[] = $recommendedApartment;
            }
            if ($recommendations->count() >= $preferredRecommendationCount) {
                break;
            }
        }

        for ($i = $recommendations->count(); $i < $preferredRecommendationCount; $i++) {
            $building = $bestOffers->shift()->first();

            if ($building == null) {
                continue;
            }

            $recommendedApartment = $building->apartments
                ->where('room_count', '>=', $mediumRoomCount - $roomCountRange)
                ->where('room_count', '<=', $mediumRoomCount + $roomCountRange)
                ->whereNotIn('offer_id', $recommendations->pluck('offer_id'))
                ->first();
            if ($recommendedApartment == null) {
                continue;
            }

            $recommendations[] = $recommendedApartment;
        }
        Log::debug('$recommendations', $recommendations->toArray());

        return $recommendations;
    }

    public function getFilteredApartmentQuery(array $validated, string $complexCode)
    {
        $building = ResidentialComplex::where('code', $complexCode)->first();
        $apartmentsQuery = $building->apartments();

        $fillQuery = function ($query, string $field, string $condition, $value) {
            if (is_array($value)) {
                $query->where(function ($query) use ($field, $condition, $value) {
                    foreach ($value as $actualValue) {
                        $actualValue = urldecode($actualValue);
                        $query->orWhere($field, $condition, $actualValue);
                    }
                });
            } else {
                $value = urldecode($value);
                $query->where($field, $condition, $value);
            }
        };

        foreach ($validated as $key => $value) {
            $searchableInApartments = in_array($key, Apartment::$searchableFields);

            if (! $searchableInApartments) {
                continue;
            }

            $field = $key;
            $condition = '=';

            if (str_ends_with($field, '-from')) {
                $field = str_replace('-from', '', $field);
                $condition = '>=';
            }

            if (str_ends_with($field, '-to')) {
                $field = str_replace('-to', '', $field);
                $condition = '<=';
            }

            if (str_ends_with($field, '-not')) {
                $field = str_replace('-not', '', $field);
                $condition = '<>';
            }

            switch ($field) {
                case 'mortgage':
                    $apartmentsQuery->whereHas('mortgageTypes', function ($mortgageTypeQuery) use ($condition, $value, $fillQuery) {
                        $fillQuery($mortgageTypeQuery, 'type', $condition, $value);
                    });
                    break;
                default:
                    $fillQuery($apartmentsQuery, $field, $condition, $value);
                    break;
            }
        }

        return $apartmentsQuery;
    }

    public function createApartment(array $fields): void
    {
        if (! isset($fields['offer_id'])) {
            Log::info('Could not create apartment with no offer id');

            return;
        }
        $apartmentModel = Apartment::where(['offer_id' => $fields['offer_id']])->first();

        if ($apartmentModel) {
            Log::info("Apartment with offer id {$fields['offer_id']} already exists");

            return;
        } else {
            $apartmentModel = Apartment::create($fields);
            $apartmentModel->formatMetaData();
            Log::info("Created apartment with offer_id {$apartmentModel->offer_id}");
        }

        //If history is missing, create it and set its creation date to apartment creation date
        if ($apartmentModel->apartmentHistory()->count() == 0) {
            $apartmentHistory['apartment_id'] = $apartmentModel->id;
            $apartmentHistory['price'] = $apartmentModel->price;
            $apartmentHistoryModel = ApartmentHistory::create($apartmentHistory);
            $apartmentHistoryModel->update(['created_at' => $apartmentModel->created_at]);
        }
    }

    public function updateApartment(Apartment $apartment, array $fields): void
    {
        if (isset($fields['price'])) {
            $this->updatePrice($apartment, $fields['price']);
        }
    }

    public function cleanUpApartmentProperties(): void
    {
        Apartment::whereNull('apartment_type')->update(['apartment_type' => 'Квартира']);
        Apartment::where(['renovation' => 'предчистовая отделка'])->update(['renovation' => 'Подготовка под чистовую отделку']);
        Apartment::where(['renovation' => 'под ключ'])->update(['renovation' => 'Отделка "под ключ"']);
        Apartment::where(['renovation' => 'Чистовая'])->update(['renovation' => 'Чистовая отделка']);
        Apartment::where(['renovation' => 'Предчистовая'])->update(['renovation' => 'Подготовка под чистовую отделку']);
        Apartment::where(['renovation' => 'да'])->update(['renovation' => 'Есть']);
        Apartment::where(['renovation' => 'нет'])->update(['renovation' => 'Без отделки']);
        Apartment::where(['renovation' => ''])->update(['renovation' => null]);
        Apartment::where(['balcony' => ''])->update(['balcony' => null]);
        Apartment::where(['bathroom_unit' => ''])->update(['bathroom_unit' => null]);
        Apartment::where(['building_state' => 'hand_over'])->update(['building_state' => 'hand-over']);
        Apartment::where(['ceiling_height' => 0])->update(['ceiling_height' => null]);
        Apartment::where(['room_count' => 0, 'apartment_type' => 'Студия'])->update(['room_count' => 1]);
    }

    public function deleteApartment(Apartment $apartment): void
    {
        Log::info("Will delete apartment with offer id {$apartment->offer_id}");
        ApartmentHistory::where('apartment_id', $apartment->id)->delete();
        MortgageType::where('apartment_id', $apartment->id)->delete();
        Renovation::where('offer_id', $apartment->offer_id)->delete();
        UserFavoritePlan::where('offer_id', $apartment->offer_id)->delete();

        $apartment->delete();
    }

    public function updatePrice(Apartment $apartment, $price): void
    {
        if ($apartment->price == $price) {
            return;
        }

        $apartment->update(['price' => $price]);
        $apartmentHistory['apartment_id'] = $apartment->id;
        $apartmentHistory['price'] = $apartment->price;

        if (ApartmentHistory::where('apartment_id', $apartment->id)->exists()) {
            $lastHistoryUpdate = ApartmentHistory::select()->where('apartment_id', $apartment->id)->get()->last();
            Log::info("Updated price for apartment with offer_id {$apartment->offer_id}");

            if (
                $lastHistoryUpdate->price != $apartment->price &&
                //Чтобы не было дубликатов
                $lastHistoryUpdate->created_at != $apartment->created_at
            ) {
                ApartmentHistory::create($apartmentHistory);
            }
        } else {
            ApartmentHistory::create($apartmentHistory);
        }
    }

    public static function getFromApp(): ApartmentService
    {
        return parent::getFromApp();
    }
}
