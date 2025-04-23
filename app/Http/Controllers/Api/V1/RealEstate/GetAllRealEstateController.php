<?php

namespace App\Http\Controllers\Api\V1\RealEstate;

use App\Http\Controllers\Controller;
use App\Http\Resources\EditableResidentialComplexResource;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetAllRealEstateController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            data: EditableResidentialComplexResource::collection(ResidentialComplex::all()),
            status: Response::HTTP_OK
        );
    }

}
