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
        'vacancies.title AS vacancy_title',
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
        'marital_statuses.title AS marital_status_title',
        'family_partner',
        'family_partner_age',
        'family_partner_relation',
        'adult_family_members_list',
        'adult_children_list',
        'candidate_profiles.serviceman',
        'candidate_profiles.is_data_processing',
        'candidate_profiles.law_breaker',
        'candidate_profiles.legal_entity',
        'candidate_profiles.comment',
    ];

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('candidate_profiles')
            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key');

        $realColumns = array_filter(self::$columns, function ($column) {
            return !in_array($column, [
                'family_partner',
                'family_partner_age',
                'family_partner_relation',
                'adult_family_members_list',
                'adult_children_list',
            ]);
        });

        $query->select($realColumns);
        $query->selectRaw("
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.name')), '') AS family_partner,
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.age')), '') AS family_partner_age,
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.relation')), '') AS family_partner_relation,

            COALESCE(
                GROUP_CONCAT(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.adult_family_members, '$[*].name')) SEPARATOR ', '),
                ''
            ) AS adult_family_members_list,

            COALESCE(
                GROUP_CONCAT(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.adult_children, '$[*].name')) SEPARATOR ', '),
                ''
            ) AS adult_children_list
        ");

        $groupByColumns = [
            'candidate_profiles.id',
            'vacancies.title',
            'marital_statuses.title',
            'candidate_profiles.status',
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
            'candidate_profiles.law_breaker',
            'candidate_profiles.legal_entity',
            'candidate_profiles.comment',
            'candidate_profiles.serviceman',
            'candidate_profiles.is_data_processing',
            'candidate_profiles.updated_at',
        ];

        $query->groupBy($groupByColumns);
        $data = $query
            ->orderBy('candidate_profiles.updated_at', 'desc')
            ->get();

        return $data->map(function ($item) {
            $item->serviceman = isset($item->serviceman) ? ($item->serviceman ? 'Да' : 'Нет') : 'Нет';
            $item->is_data_processing = isset($item->is_data_processing) ? ($item->is_data_processing ? 'Да' : 'Нет') : 'Нет';
            return $item;
        });
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
            'Супруг(а) - Имя',
            'Супруг(а) - Возраст',
            'Супруг(а) - Отношение',
            'Совершеннолетние члены семьи (ФИО)',
            'Совершеннолетние дети (ФИО)',
            'Служит ли в ВС/МВД/ФСБ?',
            'Согласие на обработку данных?',
            'Причины нарушения закона',
            'Юридический статус',
            'Комментарий',
        ];
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
