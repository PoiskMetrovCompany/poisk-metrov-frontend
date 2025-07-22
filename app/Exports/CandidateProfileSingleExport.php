<?php

namespace App\Exports;

use App\Core\Common\CandidateProfileExportColumnsConst;
use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidateProfileSingleExport implements FromCollection
{
    public string $keys;
    public function __construct(
        string $keys,
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {
        $this->keys = $keys;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $keys = explode(",", $this->keys);
        $collectData = [];

        $realColumns = array_filter(CandidateProfileExportColumnsConst::COLUMNS, function ($column) {
            return !in_array($column, [
                'family_partner',
                'family_partner_age',
                'family_partner_relation',
                'adult_family_members_list',
                'adult_children_list',
            ]);
        });

        foreach ($keys as $key) {
            $data = $this->candidateProfilesRepository->getCandidateProfiles($key, $realColumns);

            $mappedData = $data->map(function ($item) {
                $item->serviceman = isset($item->serviceman) ? ($item->serviceman ? 'Да' : 'Нет') : 'Нет';
                $item->is_data_processing = isset($item->is_data_processing) ? ($item->is_data_processing ? 'Да' : 'Нет') : 'Нет';
                return $item;
            });

            $collectData = array_merge($collectData, $mappedData->all());
        }

        return collect($collectData);
    }

    public function headings(): array
    {
        return CandidateProfileExportColumnsConst::COLUMNS_HEADER;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                foreach (range('A', 'AD') as $col) {
                    $event->sheet->getDelegate()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF5C6773'],
                ],
            ],
        ];
    }
}
