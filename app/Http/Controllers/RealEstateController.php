<?php

namespace App\Http\Controllers;

use App\Core\Services\ApartmentServiceInterface;
use App\Http\Requests\ApartmentListRequest;
use App\Models\ResidentialComplex;
use App\Services\ApartmentService;
use App\Services\CachingService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRealEstateRequest;
use App\Http\Requests\BuildingRequest;
use App\Http\Resources\EditableResidentialComplexResource;
use App\Http\Resources\ResidentialComplexResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Throwable;

/**
 * @see AppServiceProvider::registerApartmentService()
 */
class RealEstateController extends Controller
{
    public function __construct(protected ApartmentServiceInterface $apartmentService)
    {
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getAllRealEstate()
    {
        return EditableResidentialComplexResource::collection(ResidentialComplex::all());
    }

    /**
     * @param UpdateRealEstateRequest $updateRealEstateRequest
     * @return void
     */
    public function updateRealEstate(UpdateRealEstateRequest $updateRealEstateRequest)
    {
        $validated = $updateRealEstateRequest->validated();
        $realEstate = ResidentialComplex::where('id', $validated['id']);
        unset($validated['id']);
        $realEstate->update($validated);
    }

    /**
     * @param BuildingRequest $buildingRequest
     * @param string $code
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function view(BuildingRequest $buildingRequest, string $code)
    {
        $building = ResidentialComplex::where('code', $code)->first();
        $buildingResource = ResidentialComplexResource::make($building)->toArray($buildingRequest);
        $apartmentController = app()->make(ApartmentController::class);
        $validated = request()->query();
        $filteredApartments = $this->apartmentService->getFilteredApartmentQuery($validated, $code);
        $buildingResource['apartmentViews'] = $apartmentController->getDropdownContent($filteredApartments, $buildingResource['name'], [], []);

        $view = View::make('real-estate', $buildingResource);
        //TODO: use cached data
        // $cachedData = CachingService::getFromApp()->getResidentialComplex($code);
        // $cachedData['metaTags'] = json_decode(json_encode($cachedData['metaTags']), false, 512, JSON_OBJECT_AS_ARRAY);
        // $cachedData['location'] = json_decode(json_encode($cachedData['location']), false, 512, JSON_OBJECT_AS_ARRAY);
        // $cachedData['searchData'] = json_decode(json_encode($cachedData['searchData']), false, 512, JSON_OBJECT_AS_ARRAY);
        // $cachedData['apartmentViews'] = app()->make(ApartmentController::class)->getApartmentViews($code);
        // $view = View::make('real-estate', $cachedData);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectWithOldCode(Request $request)
    {
        $oldCode = $request->query('building');
        $building = ResidentialComplex::where('old_code', $oldCode)->first()->code;

        return redirect("/{$building}", 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
