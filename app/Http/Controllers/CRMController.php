<?php

namespace App\Http\Controllers;

use App\CRM\Commands\CreateLead;
use App\Models\UserAdsAgreement;
use App\Services\CRMService;
use App\Traits\KeyValueHelper;
use Illuminate\Http\Request;
use App\Services\AdsAgreementService;
use stdClass;

class CRMController extends Controller
{
    use KeyValueHelper;

    public function __construct(protected CRMService $crmService, protected AdsAgreementService $adsService)
    {
    }

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

    public function resetAdsAgreement(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required'
        ]);

        UserAdsAgreement::where('phone', $validated['phone'])->update(['agreement' => false]);
        return response()->json(['message' => 'success'], 200);
    }
}
