<?php

namespace App\Http\Controllers\Api\V1\Crm;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\CRM\Commands\CreateLead;
use App\Http\Resources\Crm\CrmResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use stdClass;

class StoreCrmController extends AbstractOperations
{
    public function __construct(
        protected AdsAgreementServiceInterface $adsService,
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'comment' => 'required',
            'city' => 'required'
        ]);

        $this->adsService->setAdsAgreement($validated['phone'], $validated['name']);

        $createLead = new CreateLead($validated['name'], $validated['phone'], $validated['comment'], $validated['city']);
        $result = $createLead->execute();
        $result = json_decode($result);
        $returned = new stdClass();

        $this->copy($result, $returned, [
            'message',
            'status_code'
        ]);

        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($returned),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_CREATED
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
