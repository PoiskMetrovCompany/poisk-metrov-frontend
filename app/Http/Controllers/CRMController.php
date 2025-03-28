<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Core\Interfaces\Services\CRMServiceInterface;
use App\CRM\Commands\CreateLead;
use App\Models\UserAdsAgreement;
use App\Providers\AppServiceProvider;
use App\Traits\KeyValueHelper;
use Illuminate\Http\Request;
use stdClass;

/**
 * @see AppServiceProvider::registerCRMService()
 * @see AppServiceProvider::registerAdsAgreementService()
 * @see CRMServiceInterface
 * @see AdsAgreementServiceInterface
 */
class CRMController extends Controller
{
    use KeyValueHelper;

    /**
     * @param CRMServiceInterface $crmService
     * @param AdsAgreementServiceInterface $adsService
     */
    public function __construct(
        protected CRMServiceInterface $crmService,
        protected AdsAgreementServiceInterface $adsService
    )
    {
    }

    /**
     * @param Request $request
     * @return stdClass
     */
    public function storeWithoutName(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required',
            'comment' => 'required',
            'city' => 'required'
        ]);

        $this->adsService->setAdsAgreement($validated['phone'], null);

        $createLead = new CreateLead("САЙТ!", $validated['phone'], $validated['comment'], $validated['city']);
        $result = $createLead->execute();
        $result = json_decode($result);
        $returned = new stdClass();

        $this->copy($result, $returned, [
            'message',
            'status_code'
        ]);

        return $returned;
    }

    /**
     * @param Request $request
     * @return stdClass
     */
    public function store(Request $request)
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

        return $returned;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetAdsAgreement(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required'
        ]);

        UserAdsAgreement::where('phone', $validated['phone'])->update(['agreement' => false]);
        return response()->json(['message' => 'success'], 200);
    }
}
