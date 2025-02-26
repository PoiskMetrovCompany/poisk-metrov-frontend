<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

/**
 * Class ExcelService.
 */
class ExcelService extends AbstractService
{
    private Collection $managersFiles;

    public function __construct(protected TextService $textService, protected CityService $cityService)
    {
        $config = Storage::json('google-managers.json');

        if (! $config) {
            Log::error('Excel config not found!');
            return;
        }

        $this->managersFiles = new Collection();
        $reader = new XlsxReader();

        foreach ($cityService->possibleCityCodes as $city) {
            $managerXlsxPath = "managers/$city/{$config[$city]['fileName']}";

            if (Storage::exists($managerXlsxPath)) {
                $this->managersFiles[$city] = $reader->load(Storage::path($managerXlsxPath));
            }
        }
    }

    public function getManagerPhonePairs(string $cityCode): Collection
    {
        $managerWithPhones = new Collection();

        if ($this->managersFiles->has($cityCode)) {
            return new Collection();
        }

        $data = $this->managersFiles[$cityCode];
        $workSheet = $data->getSheetByName('Доступ');

        foreach ($workSheet->getRowIterator(2) as $row) {
            $iterator = $row->getCellIterator('A', 'E');
            $iterator->setIterateOnlyExistingCells(true);
            $managerName = '';
            $managersPhone = '';

            foreach ($iterator as $cell) {
                if ($cell === null) {
                    continue;
                }

                $column = $cell->getColumn();
                $value = $cell->getValue();

                if ($value == null) {
                    continue;
                }

                switch ($column) {
                    case 'A':
                        $managerName = $this->textService->removeExcelFormula($value);
                        break;
                    case 'E':
                        $managersPhone = str_replace(['.', 'E10'], '', $value);
                        $managersPhone = $this->textService->removeExcelFormula($managersPhone);
                        break;
                }
            }

            if (empty($managerName) || empty($managersPhone)) {
                continue;
            }

            $managerWithPhones[$managerName] = $this->textService->formatPhone($managersPhone);
        }

        return $managerWithPhones;
    }

    public static function getFromApp(): ExcelService
    {
        return parent::getFromApp();
    }
}
