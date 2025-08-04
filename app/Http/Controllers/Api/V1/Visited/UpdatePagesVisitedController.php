<?php

namespace App\Http\Controllers\Api\V1\Visited;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddVisitedPageRequest;
use App\Http\Resources\PagesVisitedResource;
use App\Models\CRMSyncRequiredForUser;
use App\Models\VisitedPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;


class UpdatePagesVisitedController extends AbstractOperations
{
    /**
     * @param VisitedPageRepositoryInterface $visitedPageRepository
     */
    public function __construct(
        protected VisitedPageRepositoryInterface $visitedPageRepository
    )
    {
    }

    /**
     * @OA\Post(
     *       tags={"Visited"},
     *       path="/api/v1/visited/update",
     *       summary="Фиксация посещения страницы",
     *       description="Возвращение JSON объекта",
     *       security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="page", type="string", example=""),
     *              @OA\Property(property="code", type="string", example="..."),
     *          )
     *        ),
     *       @OA\Response(response=200, description="УСПЕХ!"),
     *       @OA\Response(
     *           response=404,
     *           description="Resource not found"
     *       )
     *  )
     *
     * @param AddVisitedPageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(AddVisitedPageRequest $request)
    {
        $page = $request->validated('page');
        $code = $request->validated('code');

        $userId = Auth::id();

        if ($userId == null) {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes(['status' => "User doesn't exist or not authorized"]),
                    ...self::metaData($request, $request->all())
                ],
                status: 500
            );
        }

        $conditions = [
            'user_id' => $userId,
            'page' => $page,
            'code' => $code
        ];

        if (!$this->visitedPageRepository->find($conditions)->exists()) {
            $visitedPage = $this->visitedPageRepository->store($conditions);
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes(['status' => "Created new visited page {$visitedPage->code}"]),
                    ...self::metaData($request, $request->all())
                ],
                status: Response::HTTP_CREATED
            );
        }

        CRMSyncRequiredForUser::createForCurrentUser();

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes(['status' => "Page with {$code} already exists"]),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_CREATED
        );
    }

    public function getEntityClass(): string
    {
        return VisitedPage::class;
    }

    public function getResourceClass(): string
    {
        return PagesVisitedResource::class;
    }
}
