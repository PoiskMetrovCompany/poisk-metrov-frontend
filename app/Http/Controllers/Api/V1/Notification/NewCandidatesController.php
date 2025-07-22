<?php

namespace App\Http\Controllers\Api\V1\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewCandidatesController extends Controller
{
    public function __invoke(Request $request)
    {
        $attributes = DB::connection('mongodb')
            ->table('candidate_profiles_has')
            ->select()
            ->limit(10)
            ->get();
        return new JsonResponse(
            data: [
                'response' => true,
                'attributes' => $attributes
            ],
            status: 200
        );
    }
}
