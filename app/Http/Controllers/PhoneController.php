<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Http\Requests\CallStatusIDRequest;
use App\Http\Requests\ConfirmUserRequest;
use App\Models\AuthorizationCall;
use App\Models\User;
use App\Models\UserAdsAgreement;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;

/**
 * @see AppServiceProvider::registerAdsAgreementService()
 * @see AdsAgreementServiceInterface
 */
class PhoneController extends Controller
{
    private string $campaignId;
    private string $apiKey;
    private string $flashCallURL;
    private string $callPhoneURL;

    /**
     * @param AdsAgreementServiceInterface $adsService
     */
    public function __construct(protected AdsAgreementServiceInterface $adsService)
    {
        $phoneConfig = json_decode(file_get_contents(storage_path("app/call-data.json")));

        $this->apiKey = $phoneConfig->APIkey;
        $this->campaignId = $phoneConfig->campaignId;
        $this->flashCallURL = $phoneConfig->flashCallURL;
        $this->callPhoneURL = $phoneConfig->callPhoneURL;
    }

    /**
     * @param string $URL
     * @param array $fields
     * @param string $requestType
     * @return false|mixed
     */
    function sendRequest(string $URL, array $fields, string $requestType = 'POST')
    {
        $fields['public_key'] = $this->apiKey;
        $fields['campaign_id'] = $this->campaignId;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        ]);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $requestType);

        if ($requestType != 'GET') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        } else {
            $params = http_build_query($fields);
            curl_setopt($curl, CURLOPT_URL, "{$URL}?{$params}");
        }

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response) {
            return json_decode($response);
        } else {
            return false;
        }
    }

    /**
     * @param ConfirmUserRequest $confirmUserRequest
     * @return false|string
     */
    function sendUserConfirmationMessage(ConfirmUserRequest $confirmUserRequest)
    {
        $phone = $confirmUserRequest->validated('phone');

        $isUserAds = UserAdsAgreement::where('phone', $phone)->first();

        if ($isUserAds == null) {
            $this->adsService->setAdsAgreement($phone, null);
        }

        if (User::where(['phone' => $phone, 'is_test' => true])->exists()) {
            $data = [
                'phone' => $phone,
                'pincode' => 1234,
                'call_id' => rand(1, 1000000)
            ];

            $call = AuthorizationCall::where('phone', $phone)->first();

            if ($call == null) {
                AuthorizationCall::create($data);
            } else {
                $call->update($data);
                $call->save();
            }

            return json_encode(['status' => 200]);
        }

        $fields['phone'] = $phone;
        $fields['pincode'] = $this->generateRandomVerificationCode();

        $response = $this->sendRequest($this->flashCallURL, $fields);

        if ($response == null) {
            return json_encode(['status' => 'No response', 'data' => 'No response']);
        }

        if (is_string($response->data)) {
            return json_encode(['status' => $response->status, 'data' => $response->data]);
        }

        $call = AuthorizationCall::where('phone', $phone)->first();
        $data = [
            'phone' => $phone,
            'pincode' => $response->data->pincode,
            'call_id' => $response->data->call_id
        ];

        if ($call == null) {
            AuthorizationCall::create($data);
        } else {
            $call->update($data);
            $call->save();
        }

        return json_encode(['status' => $response->status]);
    }

    /**
     * @param CallStatusIDRequest $callStatusIDRequest
     * @return false|string
     */
    function getCallStatusByPhone(CallStatusIDRequest $callStatusIDRequest)
    {
        $phone = $callStatusIDRequest->validated('phone');

        $fields['phone'] = $phone;

        $response = $this->sendRequest($this->callPhoneURL, $fields, 'GET');

        $calls = $response->data;
        $lastCallNumber = count($calls) - 1;
        $lastCall = $calls[$lastCallNumber];

        return json_encode([
            'status' => $lastCall->status,
            'status_display' => $lastCall->status_display
        ]);
    }

    function onCallConfirmed(Request $request)
    {

    }

    function onCallFailed(Request $request)
    {

    }

    /**
     * @return string
     */
    private function generateRandomVerificationCode(): string
    {
        $code = "";
        $codeLength = 4;
        for ($i = 0; $i < $codeLength; $i++) {
            $code .= rand(0, 9);
        }
        return $code;
    }
}
