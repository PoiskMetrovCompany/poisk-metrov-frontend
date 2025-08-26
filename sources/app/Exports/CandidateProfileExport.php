<?php

namespace App\Exports;

use App\Core\Common\CandidateProfileExportColumnsConst;
use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CandidateProfileExport implements FromCollection, WithHeadings, WithEvents, WithStyles, WithMapping
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $data = $this->candidateProfilesRepository->getCandidateProfiles(null, []);
        return $data;
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

    public function styles(Worksheet $sheet): array
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

    /**
     * Явное сопоставление полей к колонкам, чтобы исключить смещения и пропуски
     */
    public function map($row): array
    {
        return [
            $row->status ?? '',
            $row->vacancy_name ?? '',
            $row->last_name ?? '',
            $row->first_name ?? '',
            $row->middle_name ?? '',
            $row->reason_for_changing_surnames ?? '',
            $row->birth_date ?? '',
            $row->country_birth ?? '',
            $row->city_birth ?? '',
            $row->mobile_phone_candidate ?? '',
            $row->home_phone_candidate ?? '',
            $row->mail_candidate ?? '',
            $row->inn ?? '',
            $row->passport_series ?? '',
            $row->passport_number ?? '',
            $row->passport_issued ?? '',
            $row->permanent_registration_address ?? '',
            $row->temporary_registration_address ?? '',
            $row->actual_residence_address ?? '',
            $row->marital_status_name ?? '',
            ($row->family_partner_name ?? ''),
            ($row->family_partner_age ?? ''),
            ($row->family_partner_relation ?? ''),
            $row->adult_family_members_list ?? '',
            $row->adult_children_list ?? '',
            $row->serviceman ?? '',
            $row->is_data_processing ?? '',
            $row->law_breaker ?? '',
            $row->legal_entity ?? '',
            $row->comment ?? '',
        ];
    }
}
