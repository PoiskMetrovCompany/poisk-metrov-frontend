<?php

namespace App\Http\Controllers\Api\V1\Crm;

use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResetAdsAgreementController extends Controller
{
    /**
     * @param UserAdsAgreementRepositoryInterface $userAdsAgreementRepository
     */
    public function __construct(
        protected UserAdsAgreementRepositoryInterface $userAdsAgreementRepository,
    )
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required'
        ]);

        $this->userAdsAgreementRepository
            ->find(['phone' => $validated['phone']])
            ->update(['agreement' => false]);

        return new JsonResponse(
            data: ['message' => 'success'],
            status: Response::HTTP_OK
        );
    }
}
