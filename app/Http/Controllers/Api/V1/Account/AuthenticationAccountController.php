<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Common\CallUrlEnum;
use App\Core\Interfaces\Repositories\AuthorizationCallRepositoryInterface;
use App\Core\Interfaces\Repositories\UserAdsAgreementRepositoryInterface;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Core\Interfaces\Services\AdsAgreementServiceInterface;
use App\Core\Interfaces\Services\CallServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmUserRequest;
use App\Http\Resources\Account\AccountResource;
use App\Models\AuthorizationCall;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

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
class AuthenticationAccountController extends AbstractOperations
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
     * @param CallServiceInterface $callService
     */
    public function __construct(
        protected AdsAgreementServiceInterface $adsService,
        protected UserAdsAgreementRepositoryInterface $adsAgreementRepository,
        protected UserRepositoryInterface $userRepository,
        protected AuthorizationCallRepositoryInterface $authorizationCallRepository,
        protected CallServiceInterface $callService,
    )
    {

        $this->apiKey = config('call.api_key');
        $this->campaignId = config('call.campaing_id');
        $this->flashCallURL = CallUrlEnum::FLASH_CALL_URL->value;
        $this->callPhoneURL = CallUrlEnum::CALL_PHONE_URL->value;
    }

    /**
     * @OA\Post(
     * tags={"UserAccount"},
     * path="/api/v1/users/account/authentication/",
     * summary="Определение аккаунта",
     * description="Возвращение JSON объекта",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="phone", type="string", example="+7 (993) 952-00-85")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="УСПЕХ!",
     * @OA\JsonContent(
     * @OA\Property(property="phone", type="string", example="+7 (993) 952-00-85")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Resource not found",
     * @OA\JsonContent(
     * @OA\Property(property="error", type="string", example="Resource not found")
     * )
     * )
     * )
     *
     * @param ConfirmUserRequest $confirmUserRequest
     * @return JsonResponse
     */
    function __invoke(ConfirmUserRequest $request): JsonResponse
    {
        $phone = $request->validated('phone');

        $isUserAds = $this->adsAgreementRepository->findByPhone($phone);

        if ($isUserAds == null) {
            $this->adsService->setAdsAgreement($phone, null);
        }

        $fields['phone'] = $phone;
        $fields['pincode'] = $this->callService->generateRandomVerificationCode();

        $response = $this->callService->sendRequest($this->apiKey, $this->campaignId, $this->flashCallURL, $fields);
        if ($response == null) {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    'attributes' => ['data' => 'No response'],
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_OK
            );
        }

        if (is_string($response->data)) {
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes(['data' => $response->data]),
                    ...self::metaData($request, $request->all()),
                ],
                status: Response::HTTP_OK
            );
        }

        //TODO: вынести в сервис наверное
        $phone = $request->validated('phone');
        $pincode = $request->validated('pincode');
        $call = AuthorizationCall::where('pincode', $pincode)->where('phone', $phone)->first();
        // TODO: END

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
            data: [
                ...self::identifier(),
                ...self::attributes([]),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getResourceClass(): string
    {
        return AccountResource::class;
    }
}
