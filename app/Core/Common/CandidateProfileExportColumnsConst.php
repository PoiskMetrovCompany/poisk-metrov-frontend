<?php

namespace App\Core\Common;

final class CandidateProfileExportColumnsConst
{
    /**
     * @var array|string[]
     */
    const COLUMNS = [
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
     * @var array|string[]
     */
    const COLUMNS_HEADER = [
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

    /**
     * @var array|string[]
     */
    const GROUP_BY_COLUMNS = [
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
}
