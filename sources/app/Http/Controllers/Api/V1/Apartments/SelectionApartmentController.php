<?php

namespace App\Http\Controllers\Api\V1\Apartments;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Resources\Apartments\ApartmentCollection;
use App\Models\Apartment;
use App\Services\Apartment\SelectRecommendationsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @see SelectRecommendationsService
 */
class SelectionApartmentController extends AbstractOperations
{
    public function __construct(
        protected SelectRecommendationsService $recommendationsService,
    )
    {

    }

    public function __invoke(Request $request): JsonResponse
    {
        $attributes = $request->input('city_code') && $request->input('user_key')
            ? $this->recommendationsService->getPersonalRecommendations($request->input('user_key'), $request->input('city_code'))
            : $this->recommendationsService->getGeneralRecommendations($request->input('city_code'));

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($attributes),
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
