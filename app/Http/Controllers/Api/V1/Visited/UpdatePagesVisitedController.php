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
     * @param AddVisitedPageRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Убрал public static function
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
