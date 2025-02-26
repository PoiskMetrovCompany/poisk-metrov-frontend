<?php

namespace App\Console\Commands\Telegram;

use App\Services\CityService;
use App\Services\GoogleDriveService;
use App\Services\TextService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Storage;

class UpdateDealBotQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-deal-bot-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = 'deal-bot-questions.json';
        $infoSheet = 'Справочная';
        $configsFolder = 'deal-bot';
        $googleService = GoogleDriveService::getFromApp();
        $textService = TextService::getFromApp();
        $cityService = CityService::getFromApp();
        $questions = new Collection(Storage::json($filePath));

        foreach ($cityService->possibleCityCodes as $city) {
            $configPath = "$configsFolder/$city/config.json";
            $configJson = Storage::json($configPath);
            $config = new Collection($configJson);
            $questionsFromSheet = new Collection();
            $infoData = $googleService->getSheetData($config['fileId'], $infoSheet);
            $rowNames = $infoData[0];

            foreach ($questions as $question) {
                if (key_exists('fromColumn', $question)) {
                    $question['values'] = [];

                    for ($i = 0; $i < count($rowNames); $i++) {
                        if ($rowNames[$i] != $question['fromColumn']) {
                            continue;
                        }

                        for ($j = 1; $j < count($infoData); $j++) {
                            if (! isset($infoData[$j][$i])) {
                                continue;
                            }

                            $valueFromColumn = $infoData[$j][$i];

                            if ($valueFromColumn == '') {
                                continue;
                            }

                            $question['values'][] = $valueFromColumn;
                        }

                        $i = count($rowNames);
                    }

                    $questionsFromSheet->push($question);
                }
            }

            $config['questions'] = $questionsFromSheet;
            Storage::put($configPath, $textService->unicodeToCyrillics(json_encode($config)));
        }
    }
}
