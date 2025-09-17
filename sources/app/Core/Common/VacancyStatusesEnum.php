<?php

namespace App\Core\Common;

enum VacancyStatusesEnum: string
{
        case New ='Новая анкета';
        case Verified = 'Проверен';
        case Rejected = 'Отклонен';
        case NeedsImprovement = 'Нужна доработка';
        case Accepted = 'Принят';
        case NotAccepted = 'Не принят';
        case CameOut = 'Вышел';
        case NotCameOut = 'Не вышел';
        case StartWork = 'Вышел';
        case NotStartWork = 'Не вышел';
        case Hired = 'Принят';
        case NotHired = 'Не принят';

}
