<?php

namespace App\Http\Controllers;

use App\Core\Interfaces\Services\TextServiceInterface;
use App\Http\Requests\TelegramCallbackRequest;
use App\Models\Manager;
use App\Providers\AppServiceProvider;
use App\Services\TelegramSurveyMessageService;
use App\Services\TelegramSurveyService;
use App\Telegram\TelegramResponse;
use Illuminate\Support\Collection;
use Storage;

/**
 * @see AppServiceProvider::registerTextService()
 * @see TextServiceInterface
 */
class TelegramSurveyController extends Controller
{
    /**
     * @param TelegramSurveyService $telegramSurveyService
     * @param TextServiceInterface $textService
     * @param TelegramSurveyMessageService $surveyMessageService
     */
    public function __construct(
        protected TelegramSurveyService $telegramSurveyService,
        protected TextServiceInterface $textService,
        protected TelegramSurveyMessageService $surveyMessageService
    ) {
    }

    /**
     * @param TelegramCallbackRequest $callback
     * @return void
     */
    public function callbackStPetersburg(TelegramCallbackRequest $callback)
    {
        $this->callback($callback, 'st-petersburg');
    }

    /**
     * @param TelegramCallbackRequest $callback
     * @return void
     */
    public function callbackNovosibirsk(TelegramCallbackRequest $callback)
    {
        $this->callback($callback, 'novosibirsk');
    }

    /**
     * @param TelegramCallbackRequest $callback
     * @param string $city
     * @return void
     */
    public function callback(TelegramCallbackRequest $callback, string $city = 'novosibirsk')
    {
        if (! Storage::directoryExists('deal-bot')) {
            return;
        }

        $this->telegramSurveyService->loadCityConfig($city);
        $this->surveyMessageService->loadCityConfig($city);

        $callbackToTelegramResponse = [];
        $callbackToTelegramResponse['ok'] = true;
        $buttonData = null;
        $document = $callback->validated('message.document');
        $photo = $callback->validated('message.photo');

        if (is_array($photo) && count($photo)) {
            $photo = new Collection($photo);
            $document = $photo->last();
        }

        if (isset($callback->message)) {
            $callbackToTelegramResponse['result'] = $callback->validated('message');
            $chatId = $callback->validated('message.chat.id');
            $telegramResponse = new TelegramResponse(json_decode(json_encode($callbackToTelegramResponse)));
            $messageText = trim($telegramResponse->result->text);
            $from = $callback->validated('message.from.id');

            if ($telegramResponse->result->contact != null) {
                $phone = $this->textService->formatPhone($telegramResponse->result->contact->phoneNumber);
                $manager = Manager::where(['phone' => $phone])->first();

                if ($manager != null) {
                    $manager->update(['telegram_id' => $telegramResponse->result->contact->userId]);
                    $this->surveyMessageService->sendMessage('Ваш номер найден. Введите команду /start чтобы начать опрос.', $chatId);
                } else {
                    $this->surveyMessageService->sendMessage('Вашего номера нет в списке сотрудников.', $chatId);
                }

                return;
            }
        } else {
            $callbackToTelegramResponse['result'] = $callback->validated('callback_query');
            $chatId = $callback->validated('callback_query.message.chat.id');
            $messageText = '';
            $buttonData = $callback->validated('callback_query.data');
            $from = $callback->validated('callback_query.message.chat.id');
        }

        $this->telegramSurveyService->callback($messageText, $chatId, $buttonData, $from, $document);
    }
}
