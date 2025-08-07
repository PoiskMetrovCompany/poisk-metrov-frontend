<?php

namespace App\Core\Common;

enum MaritalStatusesEnum: string
{
    case REGISERED_MARRIAGE = 'Состою в зарегистрированном браке';
    case NOT_REGISERED_MARRIAGE = 'Состою в незарегистрированном браке';
    case NOT_MARRIAGE = 'Не состою в браке';

}
