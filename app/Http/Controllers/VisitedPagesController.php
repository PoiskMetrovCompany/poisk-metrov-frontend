<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddVisitedPageRequest;
use App\Models\CRMSyncRequiredForUser;
use App\Models\VisitedPage;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VisitedPagesController extends Controller
{
    /**
     * @return void
     */
    public static function syncVisitedPagesWithCookies()
    {
        $userId = Auth::id();

        if ($userId == null) {
            return;
        }

        $lastVisitedBuildings = Cookie::get('lastVisitedBuildings');

        if ($lastVisitedBuildings != null) {
            $lastVisitedBuildings = explode(',', $lastVisitedBuildings);

            foreach ($lastVisitedBuildings as $last) {
                VisitedPage::createForCurrentUser('real-estate', $last);
            }
        }

        $lastVisitedApartments = Cookie::get('lastVisitedApartments');

        if ($lastVisitedApartments != null) {
            $lastVisitedApartments = explode(',', $lastVisitedApartments);

            foreach ($lastVisitedApartments as $last) {
                VisitedPage::createForCurrentUser('plan', $last);
            }
        }
    }

    /**
     * @param AddVisitedPageRequest $addVisitedPageRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updatePagesVisited(AddVisitedPageRequest $addVisitedPageRequest)
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

        if (! VisitedPage::where($conditions)->exists()) {
            $visitedPage = VisitedPage::create($conditions);
            return response()->json(['status' => "Created new visited page {$visitedPage->code}"], 200);
        }

        CRMSyncRequiredForUser::createForCurrentUser();

        return response()->json(['status' => "Page with {$code} already exists"], 200);
    }
}
