<?php

namespace App\Http\Controllers\Api\V1\Account;

use App\Core\Common\RoleEnum;
use App\Core\Interfaces\Services\SmsServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\AccountSetCodeRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\AuthorizationCall;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     tags={"Account"},
 *     path="/api/v1/account/set-code",
 *     summary="Вход для кандидата.",
 *     description="Возвращение JSON объекта",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Данные для отправки кода",
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             @OA\Property(property="phone", type="string", example="+7 (999) 999-99-99"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Пользователь не найден")
 *         )
 *     )
 * )
 *
 * @param AccountSetCodeRequest $request
 * @return JsonResponse
 */
class AccountSetCodeController extends Controller
{
    private string $apiKey;
    private string $campaignId;
    private string $flashCallUrl;

    public function __construct(protected Account $account)
    {
        // Загружаем конфиг из JSON
        $config = json_decode(Storage::get('call-data.json'), true);

        $this->apiKey = $config['APIkey'];
        $this->campaignId = $config['campaignId'];
        $this->flashCallUrl = trim($config['flashCallURL']);
    }

    /**
     * @param AccountSetCodeRequest $request
     * @return JsonResponse
     */
    public function setCode(AccountSetCodeRequest $request): JsonResponse
    {
        $attributes = $request->validated();
        $phone = $attributes['phone'];

        $account = $this->account::firstOrNew(['phone' => $phone]);

        if (!$account->exists) {
            $account->fill([
                'key' => Str::uuid()->toString(),
                'role' => RoleEnum::Candidate,
            ])->save();
        }

        $fields = [
            'public_key' => $this->apiKey,
            'campaign_id' => $this->campaignId,
            'phone' => $phone,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->flashCallUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (!$response || $httpCode !== 200) {
            \Log::error('Zvonok.com API request failed', [
                'phone' => $phone,
                'http_code' => $httpCode,
                'response' => $response ?? 'no response',
            ]);

            return new JsonResponse([
                'request' => false,
                'message' => 'Не удалось отправить звонок. Попробуйте позже.'
            ], 500);
        }

        $responseData = json_decode($response, true);


        $pincode = $responseData['data']['pincode'];
        $callId = $responseData['data']['call_id'];

        AuthorizationCall::updateOrCreate(
            ['phone' => $phone],
            [
                'pincode' => $pincode,
                'call_id' => $callId,
            ]
        );

        $account->update(['secret' => Hash::make($pincode)]);

        \Log::info("Flash call sent", [
            'phone' => $phone,
            'generated_pincode' => $pincode,
            'call_id' => $callId,
        ]);

        return new JsonResponse([
            'request' => true,
            'attributes' => new AccountResource($account),
        ], 201);
    }
}
