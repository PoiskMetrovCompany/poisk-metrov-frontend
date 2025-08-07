<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Repositories\RelationshipEntityRepositoryInterface;
use App\Core\Interfaces\Services\CachingServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\SearchServiceInterface;
use App\DropdownData\ApartmentTypeDropdownData;
use App\DropdownData\AreaDropdownData;
use App\DropdownData\FinishingDropdownData;
use App\DropdownData\FloorsDropdownData;
use App\DropdownData\KitchenDropdownData;
use App\DropdownData\MaterialDropdownData;
use App\DropdownData\MetroDropdownData;
use App\DropdownData\MortgageDropdownData;
use App\DropdownData\PricesDropdownData;
use App\DropdownData\RegistrationDropdownData;
use App\DropdownData\RoomsDropdownData;
use App\DropdownData\ToiletDropdownData;
use App\DropdownData\YearsDropdownData;
use Log;
use Str;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements SearchServiceInterface
 * @property-read CachingServiceInterface $cachingService
 * @property-read CityServiceInterface $cityService
 * @property-read RelationshipEntityRepositoryInterface $relationshipEntityRepository
 */
final class SearchService extends AbstractService implements SearchServiceInterface
{
    public function __construct(
        protected CachingServiceInterface               $cachingService,
        protected CityServiceInterface                  $cityService,
        protected RelationshipEntityRepositoryInterface $relationshipEntityRepository,
    ) {
    }

    public function getSearchDataForCity(string $cityCode): array
    {
        $note = $this->relationshipEntityRepository->processingOfPlacementData($cityCode);
        $locationsInCity = $note['locationData']->pluck('id')->toArray();
        $complexAndApartmentFilter = $this->relationshipEntityRepository->complexAndApartmentFilter($locationsInCity);
        $data['apartment_count'] = $complexAndApartmentFilter['apartmentData']->count();
        $data['cheapest'] = $complexAndApartmentFilter['apartmentData']->pluck('price')->min();
        $data['most_expensive'] = $complexAndApartmentFilter['apartmentData']->pluck('price')->max();
        $data['smallest'] = $complexAndApartmentFilter['apartmentData']->pluck('area')->min();
        $data['biggest'] = $complexAndApartmentFilter['apartmentData']->pluck('area')->max();
        $data['capital'] = $note['capitals']->first();
        $data['region'] = $note['locationData']->whereNotIn('capital', $note['capitalDistricts'])->pluck('region')->first();

        // Crimea
        if ($data['region'] == null) {
            if ($note['locationData']->count()) {
                $data['region'] = $note['locationData']->first()->{'region'};
            } else {
                Log::info('No location data for [' . $cityCode . ']');
                $data['region'] = 'Неизвестно';
            }
        }

        $data['names'] = [
            'field' => 'name',
            'values' => $this->generateValues($complexAndApartmentFilter['names'])
        ];
        $data['builders'] = [
            'field' => 'builder',
            'values' => $this->generateValues($complexAndApartmentFilter['builders'])
        ];
        $data['addresses'] = [
            'field' => 'address',
            'values' => $this->generateValues($complexAndApartmentFilter['addresses'])
        ];
        $data['stations'] = [
            'field' => 'metro_station',
            'values' => $this->generateValues($complexAndApartmentFilter['stations'])
        ];
        $data['districts'] = [
            'field' => 'district',
            'values' => $this->generateValues($note['districts'])
        ];

        $dropdownData = [];
        $dropdownData['years'] = new YearsDropdownData($complexAndApartmentFilter['apartmentData']);
        $dropdownData['rooms'] = new RoomsDropdownData($complexAndApartmentFilter['apartmentData']);
        $dropdownData['prices'] = new PricesDropdownData();
        $dropdownData['floors'] = new FloorsDropdownData();
        $dropdownData['area'] = new AreaDropdownData();
        $dropdownData['kitchen_area'] = new KitchenDropdownData();
        $dropdownData['finishing'] = new FinishingDropdownData($complexAndApartmentFilter['apartmentData']);
        $dropdownData['bathroom_unit'] = new ToiletDropdownData($complexAndApartmentFilter['apartmentData']);
        $dropdownData['mortgages'] = new MortgageDropdownData($complexAndApartmentFilter['apartmentData']);
        $dropdownData['registration'] = new RegistrationDropdownData($data['capital'], $data['region']);
        $dropdownData['metro'] = new MetroDropdownData();
        $dropdownData['apartments'] = new ApartmentTypeDropdownData();
        $dropdownData['materials'] = new MaterialDropdownData($complexAndApartmentFilter['apartmentData']);

        $data['dropdownData'] = $dropdownData;

        return $data;
    }

    public function generateValues($for): array
    {
        $values = [];

        foreach ($for as $name) {
            $values[] = ['name' => $name, 'searchid' => Str::random(8)];
        }

        return $values;
    }

    public function getSearchData(): mixed
    {
        $data = $this->cachingService->getSearchFilterData($this, $this->cityService->getUserCity());

        return json_encode($data);
    }

    public static function getFromApp(): SearchService
    {
        return parent::getFromApp();
    }
}
