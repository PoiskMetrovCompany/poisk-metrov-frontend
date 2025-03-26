<?php

namespace App\Http\Controllers;

use App\Core\Services\ApartmentServiceInterface;
use App\Core\Services\PriceFormattingServiceInterface;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentHistoryResource;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\EditableApartmentResource;
use App\Http\Resources\ResidentialComplexResource;
use App\Models\Apartment;
use App\Models\Location;
use App\Models\ResidentialComplex;
use App\Services\ApartmentService;
use App\Services\PriceFormattingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Requests\ApartmentListRequest;
use Log;
use Throwable;

/**
 * @see AppServiceProvider::registerApartmentService()
 * @see AppServiceProvider::registerPriceFormattingService()
 */
class ApartmentController extends Controller
{
    public function __construct(
        protected ApartmentServiceInterface $apartmentService,
        protected PriceFormattingServiceInterface $priceFormattingService)
    {
    }

    /**
     * @param Request $request
     * @param string $offer_id
     * @return \Illuminate\Contracts\View\View|never
     */
    public function view(Request $request, string $offer_id)
    {
        $apartment = Apartment::where('offer_id', $offer_id)->first();

        if ($apartment == null) {
            return abort('404');
        }

        $complex = ResidentialComplex::where('id', $apartment->complex_id)->first();
        $location = Location::where('id', $complex->location_id)->first();
        $apartments = Apartment::where([
            'apartment_type' => $apartment->apartment_type,
            'room_count' => $apartment->room_count,
            'complex_id' => $apartment->complex_id,
            ['offer_id', '!=', $apartment->offer_id]]);

        $apartments->join('residential_complexes', 'residential_complexes.id', '=', 'apartments.complex_id');

        if (!Auth::user()) {
            $apartments->whereNotIn('residential_complexes.builder', ResidentialComplex::$privateBuilders);
//        } else {
//            $apartment->whereIn('residential_complexes.builder', ResidentialComplex::$privateBuilders);
        }

        $apartments->limit(10);
        $data = ApartmentResource::collection($apartments->get())->toArray($request);

        if ($complex->metro_type == 'foot') {
            $apartment->setAttribute('metro_type', 'пешком');
        } else if ($complex->metro_type == 'transport') {
            $apartment->setAttribute('metro_type', 'транспортом');
        } else {
            $apartment->setAttribute('metro_type', null);
        }

        $fields = [
            'address' => $complex->address,
            'metro' => $complex->metro_station,
            'metro_time' => $complex->metro_time,
            'metro_type' => $apartment->metro_type,
            'district' => $location->district,
            'floor_plan_url' => $apartment->floor_plan_url,
            'complex_name' => $complex->name,
            'similar' => $data
        ];

        //NOTE: Поменял порядок слияния (раньше первой стояла квартира), надеемся что он не был важен
        $view = View::make('plan',
            array_merge(
                $fields,
                ResidentialComplexResource::make($complex)->toArray($request),
                ApartmentResource::make($apartment)->toArray($request),
                ApartmentHistoryResource::make($apartment)->toArray($request)
            ));

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllApartments()
    {
        $allBuildings = ResidentialComplex::all();
        $apartments = new Collection();

        foreach ($allBuildings as $building) {
            $apartmentsInBuilding = $building->apartments()->get();

            foreach ($apartmentsInBuilding as $apartment) {
                $apartment->residentialComplexName = $building->name;
            }

            $apartments = $apartments->merge($apartmentsInBuilding);
        }

        return EditableApartmentResource::collection($apartments);
    }

    /**
     * @param UpdateApartmentRequest $updateApartmentRequest
     * @return void
     */
    public function updateApartment(UpdateApartmentRequest $updateApartmentRequest)
    {
        $validated = $updateApartmentRequest->validated();
        $apartment = Apartment::where('id', $validated['id']);
        unset($validated['id']);
        $apartment->update($validated);
    }

    /**
     * @param $filteredApartments
     * @param string $buildingName
     * @param $priceSortOrder
     * @param $areaSortOrder
     * @return \Illuminate\Contracts\View\View
     */
    public function getDropdownContent($filteredApartments, string $buildingName, $priceSortOrder, $areaSortOrder)
    {
        $apartmentSpecifics = [];
        $apartments = [];

        for ($i = 0; $i < 10; $i++) {
            $clone = clone $filteredApartments;
            $selection = $i == 0 ?
                $clone->select()->where('apartment_type', 'Студия') :
                $clone->select()->where([['apartment_type', '<>', 'Студия'], ['room_count', '=', $i]]);

            if ($selection == null) {
                continue;
            }

            $minPrice = $selection->min('price');

            if ($minPrice > 0) {
                $areaSorting = 'ASC';
                $priceSorting = 'ASC';

                foreach ($priceSortOrder as $key => $sortValuesForApartmentSizes) {
                    if ($key == 'Студия') {
                        if (array_key_exists($i, $sortValuesForApartmentSizes)) {
                            $priceSorting = $sortValuesForApartmentSizes[1];
                        }
                    } else if ($key == 'Квартира') {
                        if (array_key_exists($i, $sortValuesForApartmentSizes)) {
                            $priceSorting = $sortValuesForApartmentSizes[$i];
                        }
                    }
                }

                foreach ($areaSortOrder as $key => $sortValuesForApartmentSizes) {
                    if ($key == 'Студия') {
                        if (array_key_exists($i, $sortValuesForApartmentSizes)) {
                            $areaSorting = $sortValuesForApartmentSizes[1];
                        }
                    } else if ($key == 'Квартира') {
                        if (array_key_exists($i, $sortValuesForApartmentSizes)) {
                            $areaSorting = $sortValuesForApartmentSizes[$i];
                        }
                    }
                }

                $apartmentsOfType = ApartmentResource::collection(
                    $selection
                        ->get()
                        ->sortBy('area', SORT_REGULAR, $areaSorting == 'DESC')
                        ->sortBy('price', SORT_REGULAR, $priceSorting == 'DESC')
                        ->values()
                        ->all()
                );

                $currentApartmentMinData = [];
                $currentApartmentMinData['minSquare'] = $selection->min('area');
                $currentApartmentMinData['count'] = $selection->count();
                //На первой итерации берем студии, что потом уже не важно
                $currentApartmentMinData['apartmentType'] = $apartmentsOfType->first()->apartment_type;
                $currentApartmentMinData['roomCount'] = $apartmentsOfType->first()->room_count;
                $currentApartmentMinData['fullName'] = $i == 0 ? "Студии" : "{$i}-комнатные";
                $currentApartmentMinData['minPriceCatalogue'] = $this->priceFormattingService->priceToText($minPrice, ' ', ' ₽', 1);

                $apartmentSpecifics[] = $currentApartmentMinData;
                $apartments[] = $apartmentsOfType->toArray(new ApartmentListRequest());
            }
        }

        $view = View::make('real-estate.apartment-dropdown-content', [
            'apartmentSpecifics' => $apartmentSpecifics,
            'apartments' => $apartments,
            'name' => $buildingName
        ]);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @param string $complexCode
     * @return \Illuminate\Contracts\View\View
     */
    public function getApartmentViews(string $complexCode)
    {
        $building = ResidentialComplex::where('code', $complexCode)->first();
        $filteredApartments = $building->apartments();
        $priceSortOrder = [];
        $areaSortOrder = [];

        return $this->getDropdownContent($filteredApartments, $building->name, $priceSortOrder, $areaSortOrder);
    }

    /**
     * @param ApartmentListRequest $request
     * @param string $complexCode
     * @return \Illuminate\Contracts\View\View
     */
    public function getApartmentViewsWithFilters(ApartmentListRequest $request, string $complexCode)
    {
        $filteredApartments = $this->apartmentService->getFilteredApartmentQuery($request->validated(), $complexCode);
        $buildingName = ResidentialComplex::where('code', $complexCode)->first()->name;
        $priceSortOrder = $request->validated($request->priceSortOrder);
        $areaSortOrder = $request->validated($request->areaSortOrder);

        return $this->getDropdownContent($filteredApartments, $buildingName, $priceSortOrder, $areaSortOrder);
    }
}
