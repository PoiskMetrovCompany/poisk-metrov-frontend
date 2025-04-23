<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @see ApartmentRepositoryInterface
 */
class UpdateApartmentController extends AbstractOperations
{
    /**
     * @param ApartmentRepositoryInterface $apartmentRepository
     */
    public function __construct(
        protected ApartmentRepositoryInterface $apartmentRepository,
    )
    {
    }

    /**
     * TODO: сомнительное действие
     * @param UpdateApartmentRequest $request
     * @return JsonResponse
     */
    public function updateApartment(UpdateApartmentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $apartment = $this->apartmentRepository->findById($validated['id']);
        unset($validated['id']);
        $apartment->update($validated);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($apartment),
                ...self::metaData($request, $request->all())
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
        return ApartmentResource::class;
    }
}
