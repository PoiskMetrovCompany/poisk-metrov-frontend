<?php

namespace App\Exports;

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
    /**
     * @var array|string[]
     */
    protected static array $columns = [
        'candidate_profiles.status',
        'vacancies.title',
        'candidate_profiles.last_name',
        'candidate_profiles.first_name',
        'candidate_profiles.middle_name',
        'candidate_profiles.reason_for_changing_surnames',
        'candidate_profiles.birth_date',
        'candidate_profiles.country_birth',
        'candidate_profiles.city_birth',
        'candidate_profiles.mobile_phone_candidate',
        'candidate_profiles.home_phone_candidate',
        'candidate_profiles.mail_candidate',
        'candidate_profiles.inn',
        'candidate_profiles.passport_series',
        'candidate_profiles.passport_number',
        'candidate_profiles.passport_issued',
        'candidate_profiles.permanent_registration_address',
        'candidate_profiles.temporary_registration_address',
        'candidate_profiles.actual_residence_address',
        'marital_statuses.title',
//        'candidate_profiles.family_partner',
//        'candidate_profiles.adult_family_members',
//        'candidate_profiles.adult_children',
//        'candidate_profiles.serviceman',
        'candidate_profiles.law_breaker',
        'candidate_profiles.legal_entity',
//        'candidate_profiles.is_data_processing',
        'candidate_profiles.comment',
    ];

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Db::table('candidate_profiles')
            ->select(array_map(function ($column) {
                if ($column === 'candidate_profiles.birth_date' || $column === 'birth_date') {
                    return DB::raw("DATE_FORMAT(candidate_profiles.birth_date, '%d.%m.%Y') as birth_date");
                }
                return $column;
            }, self::$columns))
            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key')
            ->orderBy('candidate_profiles.updated_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Статус',
            'Вакансия',
            'Фамилия',
            'Имя',
            'Отчество',
            'Причина смены фамилии',
            'Дата рождения',
            'Страна рождения',
            'Город рождения',
            'Мобильный телефон',
            'Домашний телефон',
            'Эл. почта',
            'ИНН',
            'Серия паспорта',
            'Номер паспорта',
            'Кем выдан',
            'Адрес регистрации',
            'Временный адрес проживания',
            'Текущий адрес проживания',
            'Статус семейного положения',
//        'candidate_profiles.family_partner',
//        'candidate_profiles.adult_family_members',
//        'candidate_profiles.adult_children',
//        'candidate_profiles.serviceman',
            'Причины нарушения закона',
            'Юридический статус',
//        'candidate_profiles.is_data_processing',
            'Комментарий',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
//                $event->sheet->getSheet()->freezePane('A2');

                foreach (range('A', 'W') as $col) {
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
