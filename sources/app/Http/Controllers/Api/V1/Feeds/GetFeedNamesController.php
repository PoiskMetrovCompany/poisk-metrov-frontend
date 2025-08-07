<?php

namespace App\Http\Controllers\Api\V1\Feeds;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\FeedServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeedNameResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class GetFeedNamesController extends AbstractOperations
{
    /**
     * @param FeedServiceInterface $feedService
     */
    public function __construct(
        private FeedServiceInterface $feedService
    ) {
    }

    /**
     * @OA\Get(
     *      tags={"Feed"},
     *      path="/api/v1/feeds/get-name",
     *      summary="получение имени фида",
     *      description="Возвращение JSON объекта",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $this->feedService->getFeedNames()->sortBy('created_at', SORT_REGULAR, true);
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($data),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return 'FeedName';
    }

    public function getResourceClass(): string
    {
        return FeedNameResource::class;
    }
}
