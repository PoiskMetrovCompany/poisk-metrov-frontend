<?php

namespace App\Http\Controllers\Api\V1\RealEstate;

use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRealEstateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateRealEstateController extends Controller
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
     * @param UpdateRealEstateRequest $updateRealEstateRequest
     * @return JsonResponse
     */
    public function __invoke(UpdateRealEstateRequest $updateRealEstateRequest)
    {
        $validated = $updateRealEstateRequest->validated();
        $realEstate = $this->residentialComplexRepository->findById($validated['id']);
        unset($validated['id']);
        $realEstate->update($validated);

        return new JsonResponse(
            data: $realEstate,
            status: Response::HTTP_CREATED
        );
    }
}
