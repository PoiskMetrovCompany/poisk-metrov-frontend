<?php

namespace App\Services;
use App\Core\Interfaces\Repositories\BuilderRepositoryInterface;
use App\Core\Interfaces\Services\BuilderServiceInterface;
use App\Core\Interfaces\Services\CityServiceInterface;
use App\Core\Interfaces\Services\GoogleDriveServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use App\Models\Builder;
use App\Services\CityService;
use App\Services\GoogleDriveService;
use App\Services\TextService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Str;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements BuilderServiceInterface
 * @property-read TextServiceInterface $textService
 * @property-read CityServiceInterface $cityService
 * @property-read GoogleDriveServiceInterface $googleService
 * @property-read BuilderRepositoryInterface $builderRepository
 */
class BuilderService extends AbstractService implements BuilderServiceInterface
{
    public function __construct(
        protected TextServiceInterface $textService,
        protected CityServiceInterface $cityService,
        protected GoogleDriveServiceInterface $googleService,
        protected BuilderRepositoryInterface $builderRepository
    ) {
    }

    public function updateBuilders(): void
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

                if (!$this->builderRepository->isExists($builderAttributes)) {
                    $this->builderRepository->store($builderAttributes);
                }
            }
        }
    }

    public static function getFromApp(): BuilderService
    {
        return parent::getFromApp();
    }
}
