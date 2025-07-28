<?php

namespace App\Exports;

use App\Core\Common\CandidateProfileExportColumnsConst;
use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidateProfileSingleExport implements FromCollection, WithHeadings, WithEvents, WithStyles
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
        $collectData = collect();

        foreach ($keys as $key) {
            $data = $this->candidateProfilesRepository->getCandidateProfiles($key, []);
            $collectData = $collectData->merge($data);
        }

        return $collectData;
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
