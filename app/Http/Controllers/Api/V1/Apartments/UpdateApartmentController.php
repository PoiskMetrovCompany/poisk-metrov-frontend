<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApartmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @see ApartmentRepositoryInterface
 */
class UpdateApartmentController extends Controller
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
     * @param UpdateApartmentRequest $updateApartmentRequest
     * @return JsonResponse
     */
    public function updateApartment(UpdateApartmentRequest $updateApartmentRequest): JsonResponse
    {
        $validated = $updateApartmentRequest->validated();
        $apartment = $this->apartmentRepository->findById($validated['id']);
        unset($validated['id']);
        $apartment->update($validated);

        return new JsonResponse(
            data: $apartment,
            status: Response::HTTP_OK
        );
    }
}
