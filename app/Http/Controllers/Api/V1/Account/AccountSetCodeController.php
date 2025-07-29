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

        // Находим или создаём аккаунт
        $account = $this->account::firstOrNew(['phone' => $phone]);

        if (!$account->exists) {
            $account->fill([
                'key' => Str::uuid()->toString(),
                'role' => RoleEnum::Candidate,
            ])->save();
        }

        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // Подготавливаем данные для запроса
        $fields = [
            'public_key' => $this->apiKey,
            'campaign_id' => $this->campaignId,
            'phone' => $phone,
            'pincode' => $code,
        ];

        // Выполняем cURL-запрос к zvonok.com
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

        // Проверяем ответ
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


        // Сохраняем код и call_id для последующей проверки
        AuthorizationCall::updateOrCreate(
            ['phone' => $phone],
            [
                'pincode' => $code,
                'call_id' => $responseData['data']['call_id'],
            ]
        );

        // Обновляем secret в аккаунте
        $account->update(['secret' => Hash::make($code)]);

        return new JsonResponse([
            'request' => true,
            'attributes' => new AccountResource($account),
        ], 201);
    }
}
//class AccountSetCodeController extends Controller
//{
//    public function __construct(
//        protected Account $account,
//        protected SmsServiceInterface $smsService
//    )
//    {
//
//    }
//
//    /**
//     * @param AccountSetCodeRequest $request
//     * @return JsonResponse
//     */
//    public function setCode(AccountSetCodeRequest $request): JsonResponse
//    {
//        $attributes = $request->validated();
//        if (empty($this->account::where(['phone' => $attributes['phone']])->first())) {
//            $attributes['key'] = Str::uuid()->toString();
//            $attributes['role'] = RoleEnum::Candidate;
//            $this->account::create($attributes);
//            return $this->setCode($request);
//        } else {
//            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
//            $model = $this->account::where(['phone' => $attributes['phone']])->first();
//            $model->update(['secret' => Hash::make($code)]);
//            $this->smsService->sendCall(['phone' => $attributes['phone'], 'code' => $code]);
//        }
//        return new JsonResponse(
//            data: [
//                'request' => true,
//                'attributes' => AccountResource::make($model),
//            ],
//            status:  Response::HTTP_CREATED
//        );
//    }
//}
