<?php

namespace App\Services;
use App\Models\Builder;
use App\Services\CityService;
use App\Services\GoogleDriveService;
use App\Services\TextService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Str;

/**
 * Class BuilderService
 */
class BuilderService extends AbstractService
{
    public function __construct(
        protected TextService $textService,
        protected CityService $cityService,
        protected GoogleDriveService $googleService
    ) {
    }

    public function updateBuilders()
    {
        $infoSheet = 'БАЗА ЗАСТР.';
        $configsFolder = 'deal-bot';

        function isValidColumnValue($value)
        {
            return $value != '' && Str::length($value) <= 255;
        }

        foreach ($this->cityService->possibleCityCodes as $city) {
            $configPath = "$configsFolder/$city/config.json";

            if (! Storage::exists($configPath)) {
                continue;
            }

            $configJson = Storage::json($configPath);
            $config = new Collection($configJson);
            $infoData = $this->googleService->getSheetData($config['fileId'], $infoSheet);

            for ($rowNumber = 1; $rowNumber < count($infoData); $rowNumber++) {
                if (count($infoData[$rowNumber]) < 2) {
                    continue;
                }

                $builderAttributes['city'] = $city;
                $constructionColumn = $infoData[$rowNumber][0];
                $builderColumn = $infoData[$rowNumber][1];

                if (! isValidColumnValue($constructionColumn) || ! isValidColumnValue($builderColumn)) {
                    continue;
                }

                $builderAttributes['construction'] = trim($constructionColumn);
                $builderAttributes['builder'] = trim($builderColumn);

                if (! Builder::where($builderAttributes)->exists()) {
                    Builder::create($builderAttributes);
                }
            }
        }
    }

    public static function getFromApp(): BuilderService
    {
        return parent::getFromApp();
    }
}