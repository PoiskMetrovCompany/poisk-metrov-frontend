<?php

namespace App\Http\Controllers\Api\V1\RealEstate;

use App\Http\Controllers\Controller;
use App\Http\Resources\EditableResidentialComplexResource;
use App\Models\ResidentialComplex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

// TODO: не по JSON-API
class GetAllRealEstateController extends Controller
{
    /**
     * @OA\Get(
     *      tags={"RealEstate"},
     *      path="/api/v1/real-estate/get-all",
     *      summary="получение списка планировок",
     *      description="Возвращение JSON объекта",
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
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
