<?php

namespace App\Exports;

use App\Core\Common\CandidateProfileExportColumnsConst;
use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidateProfileExport implements FromCollection, WithHeadings, WithEvents, WithStyles
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $realColumns = array_filter(CandidateProfileExportColumnsConst::COLUMNS, function ($column) {
            return !in_array($column, [
                'family_partner',
                'family_partner_age',
                'family_partner_relation',
                'adult_family_members_list',
                'adult_children_list',
            ]);
        });

        $data = $this->candidateProfilesRepository->getCandidateProfiles(null, $realColumns);

        return $data->map(function ($item) {
            $item->serviceman = isset($item->serviceman) ? ($item->serviceman ? 'Да' : 'Нет') : 'Нет';
            $item->is_data_processing = isset($item->is_data_processing) ? ($item->is_data_processing ? 'Да' : 'Нет') : 'Нет';
            return $item;
        });
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
