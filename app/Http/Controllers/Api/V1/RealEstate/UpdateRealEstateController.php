<?php

namespace App\Http\Controllers\Api\V1\RealEstate;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRealEstateRequest;
use App\Http\Resources\ResidentialComplexResource;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateRealEstateController extends AbstractOperations
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
     * @param UpdateRealEstateRequest $request
     * @return JsonResponse
     */
    public function __invoke(UpdateRealEstateRequest $request)
    {
        $validated = $request->validated();
        $realEstate = $this->residentialComplexRepository->findById($validated['id']);
        unset($validated['id']);
        $realEstate->update($validated);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($realEstate),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function getEntityClass(): string
    {
        return ResidentialComplex::class;
    }

    public function getResourceClass(): string
    {
        return ResidentialComplexResource::class;
    }
}
