<?php

namespace App\Core\Common;

enum CallUrlEnum: string
{
    case FLASH_CALL_URL = 'https://zvonok.com/manager/cabapi_external/api/v1/phones/flashcall/';
    case CALL_PHONE_URL = 'https://zvonok.com/manager/cabapi_external/api/v1/phones/calls_by_phone/';
}
