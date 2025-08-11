<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @see ApartmentRepositoryInterface
 */
class SelectionApartmentController extends AbstractOperations
{
    public function __construct(
        protected ApartmentRepositoryInterface $apartmentRepository,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect->resource),
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
        return ApartmentCollection::class;
    }
}
