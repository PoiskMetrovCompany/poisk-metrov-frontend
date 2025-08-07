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
use OpenApi\Annotations as OA;

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
     * @OA\Post(
     *      tags={"RealEstate"},
     *      path="/api/v1/real-estate/update/",
     *      summary="Обновление планировки",
     *      description="Возвращение JSON объекта",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="1"),
     *              @OA\Property(property="h1", type="string", example="..."),
     *              @OA\Property(property="head_title", type="string", example="..."),
     *              @OA\Property(property="description", type="string", example="..."),
     *              @OA\Property(property="primary_material", type="string", example="..."),
     *              @OA\Property(property="primary_ceiling_height", type="integer", example="123"),
     *              @OA\Property(property="elevator", type="string", example="..."),
     *              @OA\Property(property="floors", type="float", example="3.14"),
     *              @OA\Property(property="corpuses", type="integer", example="123"),
     *              @OA\Property(property="parking", type="string", example="..."),
     *              @OA\Property(property="meta", type="string", example="..."),
     *              @OA\Property(property="on_main_page", type="boolean", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="УСПЕХ!",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="1"),
     *               @OA\Property(property="h1", type="string", example="..."),
     *               @OA\Property(property="head_title", type="string", example="..."),
     *               @OA\Property(property="description", type="string", example="..."),
     *               @OA\Property(property="primary_material", type="string", example="..."),
     *               @OA\Property(property="primary_ceiling_height", type="integer", example="123"),
     *               @OA\Property(property="elevator", type="string", example="..."),
     *               @OA\Property(property="floors", type="float", example="3.14"),
     *               @OA\Property(property="corpuses", type="integer", example="123"),
     *               @OA\Property(property="parking", type="string", example="..."),
     *               @OA\Property(property="meta", type="string", example="..."),
     *               @OA\Property(property="on_main_page", type="boolean", example="true"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="User not found")
     *          )
     *      )
     *  )
     *
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
