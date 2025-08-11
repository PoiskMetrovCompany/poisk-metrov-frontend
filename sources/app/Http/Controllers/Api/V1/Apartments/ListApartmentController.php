<?php

namespace App\Http\Controllers\Api\V1\Apartments;

C
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\EditableApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @see ResidentialComplexRepositoryInterface
 */
class ListApartmentController extends AbstractOperations
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
     * @OA\Get(
     *      tags={"Apartment"},
     *      path="/api/v1/apartments/list",
     *      summary="получение списка планировок",
     *      description="Возвращение JSON объекта",
     *      @OA\Parameter(
     *          name="includes",
     *          in="query",
     *          description="Указывает, какие связанные данные нужно включить",
     *          required=false,
     *          style="form",
     *          explode=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(
     *                  type="string",
     *                  enum={
     *                       "UserFavoriteBuilding",
     *                       "CRMSyncRequiredForUser",
     *                       "ResidentialComplexFeedSiteName",
     *                       "DeletedFavoriteBuilding",
     *                       "File",
     *                       "ManagerChatMessage",
     *                       "News",
     *                       "VisitedPage",
     *                       "UserFavoritePlan",
     *                       "Manager",
     *                       "Interaction"
     *                   }
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
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
            data: [
                ...self::identifier(),
                ...self::attributes($apartments),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return Apartment::class;
    }

    public function getResourceClass(): string
    {
        return EditableApartmentResource::class;
    }
}
