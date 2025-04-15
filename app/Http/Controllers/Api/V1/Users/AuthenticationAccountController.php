<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @see AppServiceProvider::registerAdsAgreementService()
 * @see AppServiceProvider::registerUserAdsAgreementRepository()
 * @see AppServiceProvider::registerUserRepository()
 * @see AppServiceProvider::registerAuthorizationCallRepository()
 * @see AdsAgreementServiceInterface
 * @see UserAdsAgreementRepositoryInterface
 * @see UserRepositoryInterface
 * @see AuthorizationCallRepositoryInterface
 */
class AuthenticationAccountController extends Controller
{
    private string $campaignId;
    private string $apiKey;
    private string $flashCallURL;
    private string $callPhoneURL;

    /**
     * @param AdsAgreementServiceInterface $adsService
     * @param UserAdsAgreementRepositoryInterface $adsAgreementRepository
     * @param UserRepositoryInterface $userRepository
     * @param AuthorizationCallRepositoryInterface $authorizationCallRepository
     */
    public function __construct(
        protected AdsAgreementServiceInterface $adsService,
        protected UserAdsAgreementRepositoryInterface $adsAgreementRepository,
        protected UserRepositoryInterface $userRepository,
        protected AuthorizationCallRepositoryInterface $authorizationCallRepository,
    )
    {
        $phoneConfig = json_decode(file_get_contents(storage_path("app/call-data.json")));

        $this->apiKey = $phoneConfig->APIkey;
        $this->campaignId = $phoneConfig->campaignId;
        $this->flashCallURL = $phoneConfig->flashCallURL;
        $this->callPhoneURL = $phoneConfig->callPhoneURL;
    }

    /**
     * @param ConfirmUserRequest $confirmUserRequest
     * @return JsonResponse
     */
    function __invoke(ConfirmUserRequest $confirmUserRequest): JsonResponse
    {
        $phone = $confirmUserRequest->validated('phone');

        $isUserAds = $this->adsAgreementRepository->findByPhone($phone);

        if ($isUserAds == null) {
            $this->adsService->setAdsAgreement($phone, null);
        }

        if ($this->userRepository->find(['phone' => $phone, 'is_test' => true])->exists()) {
            $data = [
                'phone' => $phone,
                'pincode' => 1234,
                'call_id' => rand(1, 1000000)
            ];

            $call = $this->authorizationCallRepository->findByPhone($phone);

            if ($call == null) {
                $this->authorizationCallRepository->store($data);
            } else {
                $call->update($data);
                $call->save();
            }

            return new JsonResponse(
                data: [],
                status: Response::HTTP_OK
            );
        }

        $fields['phone'] = $phone;
        $fields['pincode'] = $this->generateRandomVerificationCode();

        $response = $this->sendRequest($this->flashCallURL, $fields);

        if ($response == null) {
            return new JsonResponse(
                data: [
                    'data' => 'No response'
                ],
                status: Response::HTTP_OK
            );
        }

        if (is_string($response->data)) {
            return new JsonResponse(
                data: [
                    'data' => $response->data
                ],
                status: Response::HTTP_OK
            );
        }

        $call = $this->authorizationCallRepository->findByPhone($phone);
        $data = [
            'phone' => $phone,
            'pincode' => $response->data->pincode,
            'call_id' => $response->data->call_id
        ];

        if ($call == null) {
            $this->authorizationCallRepository->store($data);
        } else {
            $call->update($data);
            $call->save();
        }

        return new JsonResponse(
            data: [],
            status: Response::HTTP_OK
        );
    }
}
