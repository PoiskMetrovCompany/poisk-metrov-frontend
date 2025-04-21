<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\EditableApartmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

/**
 * @see ResidentialComplexRepositoryInterface
 */
class ListApartmentController extends Controller
{
    /**
     * @param ResidentialComplexRepositoryInterface $residentialComplexRepository
     */
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
    )
    {
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $allBuildings = $this->residentialComplexRepository->list([]);
        $apartments = new Collection();

        foreach ($allBuildings as $building) {
            $apartmentsInBuilding = $building->apartments()->get();

            foreach ($apartmentsInBuilding as $apartment) {
                $apartment->residentialComplexName = $building->name;
            }

            $apartments = $apartments->merge($apartmentsInBuilding);
        }

        return new JsonResponse(
            data: EditableApartmentResource::collection($apartments),
            status: Response::HTTP_OK
        );
    }
}
