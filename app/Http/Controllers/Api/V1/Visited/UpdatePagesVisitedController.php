<?php

namespace App\Http\Controllers\Api\V1\Visited;

use App\Core\Interfaces\Repositories\VisitedPageRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddVisitedPageRequest;
use App\Models\CRMSyncRequiredForUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UpdatePagesVisitedController extends Controller
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
     * @param AddVisitedPageRequest $addVisitedPageRequest
     * @return \Illuminate\Http\JsonResponse
     *
     * Убрал public static function
     */
    public function __invoke(AddVisitedPageRequest $addVisitedPageRequest)
    {
        $page = $addVisitedPageRequest->validated('page');
        $code = $addVisitedPageRequest->validated('code');

        $userId = Auth::id();

        if ($userId == null) {
            return response()->json(['status' => "User doesn't exist or not authorized"], 500);
        }

        $conditions = [
            'user_id' => $userId,
            'page' => $page,
            'code' => $code
        ];

        if (!$this->visitedPageRepository->find($conditions)->exists()) {
            $visitedPage = $this->visitedPageRepository->store($conditions);
            return response()->json(['status' => "Created new visited page {$visitedPage->code}"], 200);
        }

        CRMSyncRequiredForUser::createForCurrentUser();

        return new JsonResponse(
            data: ['status' => "Page with {$code} already exists"],
            status: Response::HTTP_OK
        );
    }
}
