<?php

namespace App\Http\Controllers\Api\V1\Event;

use App\Http\Controllers\Controller;
use App\Jobs\ReadEnentlistnerViewProfileQueue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetEventViewProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->input('profile_key')) {
            ReadEnentlistnerViewProfileQueue::dispatch($request->input('profile_key'));
        }
        return new JsonResponse(
            data: [
                'response' => true,
            ],
            status: Response::HTTP_OK
        );
    }
}
