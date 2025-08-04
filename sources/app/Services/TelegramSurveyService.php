<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Models\Builder;
use App\Models\CurrentSurvey;
use App\Models\Manager;
use App\Telegram\Survey\OptionInlineButton;
use Exception;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class TelegramSurveyService
 */
class TelegramSurveyService extends AbstractService
{
    private string $dealsFileId;
    private string $documentFolderId;
    private string $cityName;
    private string $cityCode;
    private string $dealsSheet = 'СДЕЛКИ';
    private string $infoSheet = 'Справочная';
    private string $notificationChatId;

    private Collection $questions;

    public function __construct(
        protected TextService $textService,
        protected TelegramSurveyMessageService $telegramService,
        protected GoogleDriveService $googleService
    ) {
        $this->questions = new Collection();
    }

    public function loadCityConfig(string $city)
    {
        $basePath = "deal-bot/$city";
        $this->cityCode = $city;
        $telegramSurveyConfig = Storage::json("$basePath/config.json");
        $questions = new Collection(Storage::json('deal-bot-questions.json'));
        $this->dealsFileId = $telegramSurveyConfig['fileId'];
        $this->documentFolderId = $telegramSurveyConfig['documentFolderId'];
        $this->cityName = $telegramSurveyConfig['cityName'];
        $this->notificationChatId = $telegramSurveyConfig['notificationChatId'];
        $this->telegramService->loadCityConfig($city);

        foreach ($questions as $question) {
            foreach ($telegramSurveyConfig['questions'] as $questionInConfig) {
                if ($question['tag'] == $questionInConfig['tag']) {
                    $question['values'] = $questionInConfig['values'];
                }
            }

            $this->questions[] = $question;
        }
    }

    public function callback(string $messageText, string $chatId, string|null $data, string $from, array|null $document)
    {
        $possibleManager = Manager::where(['telegram_id' => $from])->first();

        if ($possibleManager == null) {
            $parameters['reply_markup'] = [
                'keyboard' => [
                    [
                        [
                            'text' => 'Поделиться контактом',
                            'request_contact' => true
                        ]
                    ]
                ]
            ];

            $this->telegramService->sendMessage('Для авторизации в боте нажмите на кнопку "Поделиться контактом", появившуюся внизу. После снова введите команду /start', $chatId, $parameters);

            return;
        }

        if (Str::startsWith($messageText, '/')) {
            $cleanCommand = Str::replaceFirst('/', '', $messageText);

            switch ($cleanCommand) {
                case 'start':
                    if (CurrentSurvey::where('chat_id', $chatId)->exists()) {
                        $this->telegramService->sendMessage('У вас уже есть незаполненная сделка. Заполните последний пункт или нажмите на команду /stop чтобы сбросить заполнение.', $chatId);

                        return;
                    }

                    $this->deleteOldSurveys($chatId);
                    $this->telegramService->sendMessage('Ответьте на несколько вопросов.', $chatId);
                    $startStep = 'client';

                    CurrentSurvey::create([
                        'chat_id' => $chatId,
                        'agent_fio' => $possibleManager->document_name,
                        'date' => Carbon::now()->format('d.m.Y'),
                        'current_step' => $startStep
                    ]);

                    $this->sendMessage($this->questions->where('tag', $startStep)->first(), $chatId);
                    break;
                case 'stop':
                    $this->deleteOldSurveys($chatId);
                    $this->telegramService->sendMessage('Создание сделки отменено.', $chatId);
                    break;
                default:
                    $this->telegramService->sendMessage('Неизвестная команда.', $chatId);
                    break;
            }

            return;
        }

        try {
            $this->surveyStep($chatId, $messageText, $data, $document);
        } catch (Exception $e) {
            $errorMessage = 'Ошибка при выполнении шага. Попробуйте еще раз или сбросьте опрос и начните заново.';
            $fullErrorMessage = "$errorMessage\r\n\r\n{$e->getMessage()}";

            if (Str::length($fullErrorMessage) <= 4096) {
                $errorMessage = $fullErrorMessage;
            } else {
                Log::info($e->getMessage());
            }

            $this->telegramService->sendMessage($errorMessage, $chatId);
            // throw $e;
        }
    }

    private function saveDocument(array|null $document, string|null $directory, string|null $fileName)
    {
        if ($document == null && ! isset($document)) {
            return;
        }

        $file = $this->telegramService->getFile($document['file_id']);
        $fileUrl = $this->telegramService->getFileUrl($file->result->file_path);
        $extension = $this->textService->getFileExtension($file->result->file_path);

        if ($directory == null) {
            $directory = 'documents';
        }

        if ($fileName == null) {
            $fileName = $this->textService->getLastLinkPart($file->result->file_path);
        }

        $filePath = "$directory/$fileName.$extension";

        if (! Storage::directoryExists($directory)) {
            Storage::makeDirectory($directory);
        }

        Storage::put($filePath, file_get_contents($fileUrl));

        return $filePath;
    }

    private function getSimilarManagers(string|null $name): Collection
    {
        if ($name == null) {
            return new Collection();
        }

        return Manager::where('document_name', 'LIKE', "%$name%")->where('city', $this->cityCode)->get();
    }

    private function getSimilarManagerNames(string|null $name): array
    {
        if ($name == null) {
            return [];
        }

        return $this->getSimilarManagers($name)->pluck('document_name')->toArray();
    }

    private function getSimilarBuilderNames(string|null $name): array
    {
        return $this->getSimilarNames($name, 'builder', Builder::class, ['city' => $this->cityCode]);
    }

    private function getSimilarConstructionNames(string|null $name): array
    {
        return $this->getSimilarNames($name, 'construction', Builder::class, ['city' => $this->cityCode]);
    }

    public function surveyStep(string $chatId, string $messageText, string|null $data, array|null $document)
    {
        $currentSurvey = CurrentSurvey::where('chat_id', $chatId)->first();

        if ($currentSurvey == null) {
            $this->telegramService->sendMessage('Предыдущий опрос завершен. Чтобы начать новый, введите /start', $chatId);
            return;
        }

        $currentStep = $currentSurvey->current_step;
        $currQuestion = $this->questions->where('tag', $currentStep)->first();
        $nextStep = 'approximate_name';

        if ($data != null && Str::contains($data, '&&&&')) {
            $splitData = explode('&&&&', $data, 2);
            $step = $splitData[1];

            //Если пришел клик с другого шага
            if ($step != $currentStep && $step != 'confirmed') {
                return;
            }
        }

        if ($currentStep == 'confirmed') {
            if ($data == null) {
                return;
            }

            $splitData = explode('&&&&', $data, 2);
            $step = $splitData[0];

            if ($step == 'confirmed') {
                $this->finishSurvey($currentSurvey);
            } else {
                $currentSurvey->update(['current_step' => $step]);
                $nextQuestion = $this->questions->where('tag', $step)->first();
                $this->sendMessage($nextQuestion, $chatId);
            }

            return;
        }

        $i = 0;

        foreach ($this->questions as $question) {
            if ($question['tag'] == $currentStep && $i + 1 < count($this->questions)) {
                $nextStep = $this->questions[$i + 1]['tag'];
                break;
            }

            $i++;
        }

        switch ($currQuestion['tag']) {
            case 'construction':
                if ($currentSurvey->construction == 'awaitingInput') {
                    $currQuestion['type'] = 'text';
                }
                break;
            case 'builder':
                if ($currentSurvey->builder == 'awaitingInput') {
                    $currQuestion['type'] = 'text';
                }
                break;
        }

        $dataToSave = '';

        if ($currQuestion['type'] == 'text') {
            if ($data != null) {
                return;
            }

            $numericTags = new Collection(['price', 'commission']);

            if ($numericTags->contains($currQuestion['tag'])) {
                $formattedText = Str::replace(new Collection([' ', '.', ',']), '', $messageText);

                if (! is_numeric($formattedText)) {
                    $this->telegramService->sendMessage('Значение должно содержать только цифры. Попробуйте снова.', $chatId);
                    return;
                }

                $dataToSave = $formattedText;
            } else {
                $dataToSave = $messageText;
            }
        }

        if ($currQuestion['type'] == 'inline') {
            if ($data == null) {
                $this->telegramService->sendMessage('Выберите один из вариантов.', $chatId);

                return;
            }

            if (Str::contains($data, '&&&&')) {
                $splitData = explode('&&&&', $data, 2);
                $selected = $splitData[0];
            } else {
                $selected = $data;
            }

            if ($currQuestion['withTable']) {
                switch ($currQuestion['baseTable']) {
                    case 'builders':
                        switch ($selected) {
                            case -1:
                                if ($currQuestion['tag'] == 'builder') {
                                    $currentSurvey->update(['builder' => 'awaitingInput']);
                                    $this->telegramService->sendMessage('Введите название застройщика. Это название будет записано напрямую.', $chatId);
                                } else {
                                    $currentSurvey->update(['construction' => 'awaitingInput']);
                                    $this->telegramService->sendMessage('Введите название стройки. Это название будет записано напрямую.', $chatId);
                                }
                                return;
                            default:
                                $builder = Builder::where('id', $selected)->first();

                                if ($currQuestion['tag'] == 'builder') {
                                    $dataToSave = $builder->builder;
                                } else {
                                    $dataToSave = $builder->construction;
                                }
                                break;
                        }
                        break;
                    case 'managers':
                        $manager = Manager::where('id', $selected)->first();
                        $dataToSave = $manager->document_name;
                        break;
                    default:
                        break;
                }
            } else {
                $dataToSave = $currQuestion['values'][$selected];
            }
        }

        switch ($currQuestion['tag']) {
            //Пропускаем заполнение стройки и застройщика если сделка не на первичку
            case 'is_first':
                if ($dataToSave != 'Первичка') {
                    $nextStep = 'address';
                    $currentSurvey->update([
                        'approximate_builder' => null,
                        'approximate_construction' => null,
                        'construction' => null,
                        'builder' => null
                    ]);
                }
                break;
            //Пропускаем заполнение адреса если сделка на первичку и мы уже заполнили стройку и застройщика
            case 'builder':
                if ($currentSurvey->is_first == 'Первичка') {
                    $nextStep = 'is_lead';
                    $currentSurvey->update([
                        'address' => null,
                    ]);
                }
                break;
            //Пропускаем заполнение комиссии если комиссия уже рассчитывается из процента
            case 'builder_percent':
                if (Str::lower($dataToSave) != 'фикс') {
                    $nextStep = 'place';
                    $dataToSave = Str::replace('.', ',', $dataToSave);

                    if (! $this->textService->isValidPercentNumber($dataToSave)) {
                        $this->telegramService->sendMessage('Вводите только цифры или символы.', $chatId);

                        return;
                    }

                    if (! Str::endsWith($dataToSave, '%')) {
                        $dataToSave = "$dataToSave%";
                    }
                } else {
                    $dataToSave = '';
                }
                break;
            default:
                break;
        }

        if (
            $currentSurvey->awaiting_confirmation &&
            ! (array_key_exists('ignoreWaitConfirmation', $currQuestion) &&
                $currQuestion['ignoreWaitConfirmation'] == true)
        ) {
            $currentStep = 'confirmed';
        }

        $allowDocumentSkip = env('app.deal_allow_skip_file_upload');

        if ($currQuestion['tag'] == 'document') {
            //TODO: доделать переключатель
            if (
                $document == null
                // && Str::lower($messageText) != 'пропуск'
                // && ! $allowDocumentSkip
            ) {
                $this->telegramService->sendMessage('Отправьте файл как документ.', $chatId);

                return;
            }

            if ($document != null) {
                $dataToSave = $this->saveDocument($document, 'documents', $currentSurvey->id);
            } else {
                $dataToSave = '';
            }
        }

        $fieldToSave = $currQuestion['tag'];

        $currentSurvey->update([
            $fieldToSave => $dataToSave,
            'current_step' => $nextStep
        ]);

        if ($currQuestion['type'] == 'inline') {
            $this->telegramService->sendMessage("Вы выбрали: $dataToSave", $chatId);
        }

        $nextQuestion = $this->questions->where('tag', $nextStep)->first();
        $lastStep = $this->questions->last()['tag'];

        if ($currentStep == $lastStep || $currentStep == 'confirmed') {
            $surveyToSend = CurrentSurvey::where('chat_id', $chatId)->first();

            if ($surveyToSend->confirmed == true) {
                $this->finishSurvey($surveyToSend);

                return;
            }

            $surveyToSend->update(['current_step' => 'confirmed', 'awaiting_confirmation' => true]);
            $this->askForConfirmation($chatId);
        } else {
            $this->sendMessage($nextQuestion, $chatId);
        }
    }

    private function getCityFolder(): DriveFile
    {
        $documentFolder = $this->googleService->getFolder($this->documentFolderId);
        $rootFileList = $this->googleService->getFileListFromFolder($documentFolder->id);
        $cityFolder = null;

        foreach ($rootFileList->getFiles() as $fileOrFolder) {
            if ($fileOrFolder->getName() == $this->cityName) {
                $cityFolder = $fileOrFolder;
            }
        }

        if ($cityFolder == null) {
            $cityFolder = $this->googleService->createFolder($this->cityName, [$this->documentFolderId]);
        }

        return $cityFolder;
    }

    private function getYearFolder(): DriveFile
    {
        $cityFolder = $this->getCityFolder();
        $currentYear = Carbon::now()->year;

        $cityYearsFolders = $this->googleService->getFileListFromFolder($cityFolder->id);
        $currentYearFolder = null;

        foreach ($cityYearsFolders->getFiles() as $yearFolder) {
            if ($yearFolder->getName() == $currentYear) {
                $currentYearFolder = $yearFolder;
            }
        }

        if ($currentYearFolder == null) {
            $currentYearFolder = $this->googleService->createFolder($currentYear, [$cityFolder->id]);
        }

        return $currentYearFolder;
    }

    private function getMonthFolder(): DriveFile
    {
        $currentYearFolder = $this->getYearFolder();
        $currentMonth = $this->textService->toUpper(Carbon::now()->locale('ru_RU')->monthName) . ' ' . Carbon::now()->year;

        $monthInYearFolders = $this->googleService->getFileListFromFolder($currentYearFolder->id);
        $currentMonthFolder = null;

        foreach ($monthInYearFolders->getFiles() as $monthFolder) {
            if ($monthFolder->getName() == $currentMonth) {
                $currentMonthFolder = $monthFolder;
            }
        }

        if ($currentMonthFolder == null) {
            $currentMonthFolder = $this->googleService->createFolder($currentMonth, [$currentYearFolder->id]);
        }

        return $currentMonthFolder;
    }

    private function finishSurvey(CurrentSurvey $surveyToSend)
    {
        $monthFolder = $this->getMonthFolder();
        $fileName = "{$surveyToSend->client}_{$surveyToSend->date}";
        $documentOnDrive = $this->googleService->uploadFile(Storage::path($surveyToSend->document), $fileName, [$monthFolder->id]);
        $documentLink = $documentOnDrive->webViewLink;

        $valuesToSheet = [
            '',
            $surveyToSend->date,
            $surveyToSend->agent_fio,
            $surveyToSend->client,
            $surveyToSend->is_first,
            $surveyToSend->construction,
            $surveyToSend->builder,
            $surveyToSend->address,
            $surveyToSend->is_lead,
            $surveyToSend->price,
            $surveyToSend->builder_percent,
            $surveyToSend->commission,
            '',
            '',
            '',
            '',
            '',
            $surveyToSend->place,
            '',
            '',
            '',
            '',
            '',
            $documentLink
        ];
        $this->googleService->addRowToSheet($this->dealsFileId, $valuesToSheet, $this->dealsSheet);
        $this->telegramService->sendMessage("Опрос окончен, введенные данные сохранены. Чтобы начать новый опрос, введите /start", $surveyToSend->chat_id);
        $this->sendSurveySummary($surveyToSend);
        $this->deleteOldSurveys($surveyToSend->chat_id);
    }

    private function sendSurveySummary(CurrentSurvey $survey)
    {
        $summary = $survey->getSummary()->toArray();
        $surveyAsText = '';

        foreach ($summary as $entryName => $entryValue) {
            $surveyAsText .= "\r\n$entryName: <b>$entryValue</b>";
        }

        $manager = Manager::where('telegram_id', $survey->chat_id)->first();
        $message = "$surveyAsText\r\n\r\n<a href=\"tg://user?id={$manager->telegram_id}\">{$manager->document_name}</a>";
        $parameters = ['parse_mode' => 'HTML'];

        $this->telegramService->sendMessage($message, $this->notificationChatId, $parameters);
    }

    private function askForConfirmation(string $chatId)
    {
        $surveyToSend = CurrentSurvey::where('chat_id', $chatId)->first();
        $summary = $surveyToSend->getSummary()->toArray();
        $surveyAsText = '';
        $buttons = [];

        foreach ($summary as $entryName => $entryValue) {
            if ($entryName == 'Дата') {
                continue;
            }

            $surveyAsText .= "\r\n$entryName: <b>$entryValue</b>";
            $buttons[] = (new OptionInlineButton($entryName, "{$surveyToSend->fieldsForDisplays[$entryName]}&&&&{$surveyToSend->current_step}"))->toArray();
        }

        $buttons[] = (new OptionInlineButton('Подтвердить', 'confirmed&&&&confirmed'))->toArray();
        $confirmMessage = "Ваша сделка. Нажмите подтвердить чтобы завершить заполнение сделки или выберите опцию, которую хотите поменять.\r\n$surveyAsText";
        $parameters = ['parse_mode' => 'HTML'];

        foreach ($buttons as $button) {
            $parameters['reply_markup']['inline_keyboard'][] = [$button];
        }

        $this->telegramService->sendMessage($confirmMessage, $chatId, $parameters);
    }

    public function sendMessage(array $question, string $chatId)
    {
        $text = $question['text'];

        if ($question['type'] === 'inline') {
            $buttons = [];
            $parameters = [];
            $values = [];
            $currentSurvey = CurrentSurvey::where('chat_id', $chatId)->first();

            if ($question['withTable']) {
                switch ($question['baseTable']) {
                    case 'managers':
                        $managers = $this->getSimilarManagers($currentSurvey->approximate_name);

                        if (! $managers->count()) {
                            $currentSurvey->update(['current_step' => 'approximate_name']);
                            $this->telegramService->sendMessage('Таких имени или фамилии не найдено, попробуйте еще раз.', $chatId);

                            return;
                        }

                        foreach ($managers as $manager) {
                            $values[] = ['text' => $manager->document_name, 'id' => $manager->id];
                        }
                        break;
                    case 'builders':
                        $builders = Builder::whereNotNull(['builder', 'construction'])->get();

                        if ($question['tag'] == 'builder') {
                            $similarNames = $this->getSimilarBuilderNames($currentSurvey->approximate_builder);
                            $builders = $builders->unique('builder')->whereIn('builder', $similarNames);

                            foreach ($builders as $builder) {
                                $values[] = ['text' => $builder->builder, 'id' => $builder->id];
                            }
                        } else {
                            $similarNames = $this->getSimilarConstructionNames($currentSurvey->approximate_construction);
                            $builders = $builders->whereIn('construction', $similarNames);

                            foreach ($builders as $builder) {
                                $values[] = ['text' => $builder->construction, 'id' => $builder->id];
                            }
                        }

                        $values[] = ['text' => 'Ввести название', 'id' => -1];
                        break;
                }

                foreach ($values as $value) {
                    $buttons[] = (new OptionInlineButton($value['text'], "{$value['id']}&&&&{$currentSurvey->current_step}"))->toArray();
                }

            } else {
                foreach ($question['values'] as $i => $value) {
                    $buttons[] = (new OptionInlineButton($value, "{$i}&&&&{$currentSurvey->current_step}"))->toArray();
                }
            }

            foreach ($buttons as $button) {
                $parameters['reply_markup']['inline_keyboard'][] = [$button];
            }

            $this->telegramService->sendMessage($text, $chatId, $parameters);

            return;
        }

        $this->telegramService->sendMessage($text, $chatId);
    }

    public function deleteOldSurveys(string $chatId)
    {
        $survey = CurrentSurvey::where('chat_id', $chatId)->first();

        if ($survey == null) {
            return;
        }

        $files = Storage::files('documents');

        foreach ($files as $file) {
            $fileName = $this->textService->getLastLinkPart($file);

            if (Str::startsWith($fileName, $survey->id)) {
                Storage::delete($file);
            }
        }

        $survey->delete();
    }
}
