<?php

namespace App\Core\Common;

enum CandidateProfileStatusesEnum: string
{
    case NEW = 'Новая анкета';
    case VERIFIED = 'Проверен';
    case REVISION = 'Нужна доработка';
    case REJECTED = 'Отклонен';
}
