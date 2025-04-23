<?php

namespace App\Http\Controllers\Api\V1\Crm;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Crm\CrmResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResetAdsAgreementController extends AbstractOperations
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
            data: [
                ...self::identifier(),
                ...self::attributes(['message' => 'success']),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return 'Crm';
    }

    public function getResourceClass(): string
    {
        return CrmResource::class;
    }
}
